<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\productModel;

class productController extends Controller
{
    public function getProducts(){
    	$products = productModel::getAllProducts();
    	$recommendProducts = productModel::getRecProducts(1);
    	 return view('index' , [ 'products' => $products , 'recommendProducts' =>  $recommendProducts  ]);
    	// return $products;
    }
}
