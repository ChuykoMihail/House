<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
class ProductsController extends Controller
{
    public function allProduct()
    {
      return Product::all();
    }
    public function categoryProduct($category_id){
      return Product::where('categories_id',$category_id)->get();
      // view('list-of-products', [
      //       'products' =>$products
      //   ]);
    }

    public function productById($id){
      return Product::where('id',$id)->get();
    }
}
