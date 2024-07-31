<?php

namespace App\Helper;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;

class Cart
{
    public static function getCount(): int
    {
        if ($user = auth()->user()) {
            return CartItem::where('user_id',$user->id)->count(); //sum('quantity')
        } else {
            return array_reduce(self::getCookieCartItems(), fn ($carry) => $carry + 1, 0);
            // arrow function akan membuat nilai += 1, yang mana dimulai dari 0
        }
    }

    public static function getCartItems()
    {
        if ($user = auth()->user()) {
            return CartItem::where('user_id',$user->id)->get()->map(fn (CartItem $item) => ['product_id' => $item->product_id, 'quantity' => $item->quantity]);
            // fn (arrow function) yang digunakan di atas adalah bagian dari sintaks PHP 7.4 ke atas. Ini adalah cara singkat dan ekspresif untuk mendefinisikan fungsi anonim.
        // CartItem::where('user_id',$user->id)->get(): Ini adalah query untuk mengambil semua item keranjang belanja dari tabel cart_items yang memiliki user_id yang sesuai dengan ID pengguna yang diberikan.

        // ->map(fn (CartItem $item) => ['product_id' => $item->product_id, 'quantity' => $item->quantity]): Ini adalah metode untuk memetakan setiap item dalam koleksi hasil query menjadi larik asosiatif dengan kunci product_id dan quantity. Setiap item dari kueri adalah instance dari model CartItem, dan fungsi panjang digunakan untuk mengonversi setiap item menjadi larik dengan kunci product_id yang menyimpan ID produk dan quantity yang menyimpan jumlahnya.

        // Jadi, hasil akhir dari kode ini adalah larik yang berisi item-item keranjang belanja pengguna tertentu dengan format array [ 'product_id' => ..., 'quantity' => ...] yang dapat digunakan lebih lanjut dalam aplikasi Anda.
        } else {
            return self::getCookieCartItems();
        }
    }

    public static function getCookieCartItems()
    {
        return json_decode(request()->cookie('cart_items', '[]'), true);
        // cari cookie carts_items, jika tidak ada maka default kosong
        // kemudian jika ada cookie carts_items yg awalanya json akan dirubah ke aaray assos versi php
        // true berguna agar memastikan nilai json berubah menjadi array assos
    }

    public static function setCookieCartItems(array $cartItems)
    {
        Cookie::queue('cart_items', json_encode($cartItems), 60*24*30);
        // artinya tambahkan cookie cart_items yg mana nilainya dari argument cartItems yg dimasukan
        // cookie akan berfungsi selama 60 menit * 24 * 30 // ini akan menjadi 30 hari
    }

    public static function saveCookieCartItems()
    {
        $user = auth()->user();
        $userCartItems = CartItem::where(['user_id' => $user->id])->get()->keyBy('product_id');
        // carikan cartItem dengan userid tertentu, kemudian hasil get dari CartItem akan dirubah menjadi array assoc yg mana memiliki key product_id
        $savedCartItems = [];
        foreach (self::getCookieCartItems() as $cartItem) {
            if (isset($userCartItems[$cartItem['product_id']])) { //mengecek apakah dicookie usertertentu memiliki history pemesanan
                $userCartItems[$cartItem['product_id']]->update(['quantity' => $cartItem['quantity']]);
                continue;
            }
            $savedCartItems[] = [
                'user_id' => $user->id,
                'product_id' => $cartItem['product_id'],
                'quantity' => $cartItem['quantity'],
            ];
        }
        if (!empty($savedCartItems)) { //jika datanya tidak kosong maka tambahkan
            CartItem::insert($savedCartItems);
        }
    }

    public static function moveCartItemsIntoDb()
    {
        $request = request();
        $cartItems = self::getCookieCartItems();
        $newCartItems = [];
        foreach ($cartItems as $cartItem) {
            // Check if the record already exists in the database
            $existingCartItem = CartItem::where([
                'user_id' => $request->user()->id,
                'product_id' => $cartItem['product_id'],
            ])->first();

            if (!$existingCartItem) {
                // Only insert if it doesn't already exist
                $newCartItems[] = [
                    'user_id' => $request->user()->id,
                    'product_id' => $cartItem['product_id'],
                    'quantity' => $cartItem['quantity'],
                ];
            }
        }


        if (!empty($newCartItems)) {
            // Insert the new cart items into the database
            CartItem::insert($newCartItems);
        }
    }


    public static function getProductsAndCartItems()
    {
        $cartItems = self::getCartItems();

        $ids = Arr::pluck($cartItems, 'product_id'); //ambil nilai dengan key product_id dalam array cartItems
        $products = Product::whereIn('id', $ids)->with('productImage')->get();
        $cartItems = Arr::keyBy($cartItems, 'product_id');
        // disini kita akan membuat collection baru yg mana data dari cartsItem akan di buat berdasarkan product_id, intinya akan membuat array assoc yg mana keynya dari product_id
        return [$products, $cartItems];
    }
}