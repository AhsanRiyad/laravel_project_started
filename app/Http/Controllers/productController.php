<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\productModel;

class productController extends Controller
{
    public function getProducts(){
    	$products = productModel::getAllProducts();
    	 return view('index' , [ 'products' => $products]);
    	// return $products;
    }
}
