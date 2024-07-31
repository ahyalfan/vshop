<?php

namespace App\Http\Controllers;

use App\Helper\Cart;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;

class CartController extends Controller
{
    public function view(Request $request, Product $product){
        $user = auth()->user();
        if($user){
            $cartItems = CartItem::where("user_id", $user->id)->get();
            $userAddress = UserAddress::where("user_id", $user->id)->where('isMain',1)->first();
            if($cartItems->count() > 0){
                return Inertia::render('User/CartList', [
                    'cartItems'=> $cartItems,
                    'userAddress'=> $userAddress,
                    ]);
            }else{
                return Inertia::render('User/CartList');
            }
        } //jika tidak ada user hanya guest maka kita bisa coba ambil cookienya
        else {
            $cartItems = Cart::getCookieCartItems();
            if (count($cartItems) > 0) {
                $cartItems = new CartResource(Cart::getProductsAndCartItems());//coba rubah ke bentuk resource
                return Inertia::render('User/CartList', ['cartItems' => $cartItems]);
            } else {
                return redirect()->back();
            }
        }
    }
    public function store(Request $request,Product $product){
        $quantity = $request->post("quantity",1); //kita kasih post(quantity,1), agar request quantity memliki nilai default 1,jika request quantity tidak dimasukan
        $user = $request->user(); // ini sama Auth:user, intinya ambil user auth

        if ($user) {
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();
            if ($cartItem) { //cek jika ada maka tambahkan
                $cartItem->increment('quantity');
            } else {
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                ]);
            }
        } else { // ini jika tidak ada user atau guest ingin memesan
            $cartItems = Cart::getCookieCartItems();
            $isProductExists = false;
            $i = 0;
            foreach ($cartItems as $item) {
                if ($cartItems[$i]['product_id'] === $product->id) {
                    $cartItems[$i]['quantity'] += $quantity;
                    // $item['quantity'] = 10;// ini tidak bisa entah mengapa
                    $isProductExists = true;
                    break;
                }else{
                    $i += 1;
                    continue;
                }
            }

            if (!$isProductExists) {
                $cartItems[] = [
                    'user_id' => null,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ];
            }
            Cart::setCookieCartItems($cartItems); // kita akan set cartItem nya kedalam cookie
        }

        return redirect()->back()->with('success', 'cart added successfully ');
    }

    public function update(Request $request,Product $product){
        $quantity = $request->integer('quantity'); //cek request quanity terbaru
        $user = $request->user();
        if ($user) {
            CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->update(['quantity' => $quantity]);
        } else { //jika guest
            $cartItems = Cart::getCookieCartItems();
            foreach ($cartItems as &$item) { //& artinya $item dapat dipengaruhi di foreach
                if ($item['product_id'] === $product->id) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }
            Cart::setCookieCartItems($cartItems); //setCookie yg terbaru
        }

        return redirect()->back();
    }
    
    public function delete(Request $request, Product $product){
        $user = $request->user();
        if ($user) {
            CartItem::query()->where(['user_id' => $user->id, 'product_id' => $product->id])->first()?->delete();
            if (CartItem::count() <= 0) {
                return redirect()->route('home')->with('info', 'your cart is empty');
            } else {
                return redirect()->back()->with('success', 'item removed successfully');
            }
        } else {
            $cartItems = Cart::getCookieCartItems();
            foreach ($cartItems as $i => &$item) { //& ini membuat$ $item dapat dipengaruhi/dirubah didalam foreach
                if ($item['product_id'] === $product->id) {
                    array_splice($cartItems, $i, 1);
                    break;
                }
            }
            Cart::setCookieCartItems($cartItems);
            if (count($cartItems) <= 0) {
                return redirect()->route('user.home')->with('info', 'your cart is empty');
            } else {
                return redirect()->back()->with('success', 'item removed successfully');
            }
        }
    }
}
