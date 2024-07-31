<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Categories;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductListController extends Controller
{
    public function index(){
        $products = Product::with(['brand','categories','productImage']);

        $filterProducts = $products->filtered()->paginate(9)->withQueryString(); //disini coba kita pagination, berdarkan  filter yg sudah kita buat model product

        $brands = Brand::all();
        $categories = Categories::all();
        return Inertia::render("User/ProductList", [
            "products"=> ProductResource::collection($filterProducts),
            'brands' => $brands,
            'categories'=> $categories,
        ]);
    }
}
