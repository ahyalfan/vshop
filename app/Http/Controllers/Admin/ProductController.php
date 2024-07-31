<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Categories;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(){
        $products = Product::with(['brand','categories','productImage'])->get(); //kita kasih with untuk dapat mengakses relasi database
        $brands = Brand::all();
        $categories = Categories::all();
        return Inertia::render("Admin/Product/Index", [
            "products"=> $products,
            "categories"=> $categories,
            'brands' => $brands,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'=>['required','max:100','unique:products,title'],
            'price' => ['required','numeric'],
            'quantity' => ['required','numeric'],
            // 'product_images' => ['image','mimes:jpeg,png,jpg,gif,svg','max:2048']//artinya harus imange berformat jpeg,png dan maksimal ukurannya 2048
        ]);

        // \dd($request->file('product_images')); //ini utuk lihat apakah product_image berasil dikirim
        // Kode \dd($request->file('product_images')); adalah perintah untuk mencetak dan men-debug nilai dari file yang diunggah dengan nama product_images pada objek Request dalam Laravel. Perintah dd() adalah singkatan dari "dump and die", yang berarti ia akan mencetak nilai yang diberikan dan kemudian menghentikan eksekusi kode selanjutnya.
        $product = new Product();
        $product->title = $request->title;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->description = $request->description;
        $product->categories_id = $request->categories_id;
        $product->brand_id = $request->brand_id;
        $product->save();

        // check apkah product memiliki image
        if($request->hasFile("product_images")){
            $images = $request->file("product_images");
            // kita lakukan pengulangan karena ada kemungkinan gambar bisa lebih dari satu
            foreach ($images as $image) {
                // kita ubah namenya menjadi uniq
                $uniqName = \time()."-".Str::random(10).".".$image->getClientOriginalExtension();
                // kita pindahkan ke folder public
                $image->move(\public_path('product_images'), $uniqName); //kita simpan ke product_images kemudian nama filenya sesuai uniqname yg sudah kita buat
                // tambahkan datanya ke dalam database
                ProductImage::create([
                    'product_id'=> $product->id,
                    "image" => 'product_images/'.$uniqName,
                ]);
            }
        }
        // langsung kemablikan ke halaman product
        return redirect()->route("admin.product.index")->with("success","Created Product success");
    }

    public function update(Request $request, $id){
        $request->validate([
            'title'=>['required','max:100','unique:products,title'],
            'price' => ['required','numeric'],
            'quantity' => ['required','numeric'],
            // 'product_images' => ['image','mimes:jpeg,png,jpg,gif,svg','max:2048']//artinya harus imange berformat jpeg,png dan maksimal ukurannya 2048
        ]);
        $product = Product::findOrFail($id);

        // dd($product); 
        // mencoba memasukan request updated
        $product->title = $request->title;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->description = $request->description;
        $product->categories_id = $request->categories_id;
        $product->brand_id = $request->brand_id;
        // Check if product images were uploaded
        if ($request->hasFile('product_images')) {
            $productImages = $request->file('product_images');
            // Loop through each uploaded image
            foreach ($productImages as $image) {
                // Generate a unique name for the image using timestamp and random string
                $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();

                // Store the image in the public folder with the unique name
                $image->move(public_path('product_images'), $uniqueName);

                // Create a new product image record with the product_id and unique name
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => 'product_images/' . $uniqueName,
                ]);
            }
        }
        $product->update();
        return redirect()->route('admin.product.index')->with('success','updated success bro');
    }

    public function deleteImage($id){
        $idImage = ProductImage::find($id);
        if(isset($idImage->image)){
            $img_name = $idImage->image;
            
            File::delete(\public_path($img_name));
        }
        ProductImage::where('id',$id)->delete();
        return redirect()->route('admin.product.index')->with('success','delete image succes');
    }

    public function destroy($id){
        $idImage = ProductImage::where('product_id',$id)->get(); //cek apakah ada product image
        if(isset($idImage)){ //jika ada delete
            foreach($idImage as $images){ 
                $img_name = $images->image;
                File::delete(\public_path($img_name));
            } 

            ProductImage::where('product_id',$id)->delete();
        }
        Product::where('id',$id)->delete();
        return redirect()->route('admin.product.index')->with('success','deleted product Success');
    }
}