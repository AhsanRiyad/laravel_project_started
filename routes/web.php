<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'productController@getProducts' )->Name('index');

Route::get('/product/details/{pid}', 'productController@getProductDetails' )->Name('product.details');

Route::post('/product/postReview', 'productController@postReview' )->Name('product.postReview');

Route::post('/addtocart', 'productController@addtocart' )->Name('productController.addtocart');

Route::get('/product/search', 'productController@searchProducts' )->Name('product.searchProducts');

Route::get('/cart', 'productController@cart' )->Name('product.cart');


Route::get('/login', 'authenticationController@login' )->Name('authentication.login');

Route::post('/login', 'authenticationController@loginCheck' )->Name('authentication.login');

Route::get('/signup', 'authenticationController@signUp' )->Name('authentication.signup');

Route::post('/signup', 'authenticationController@signUpCheck' )->Name('authentication.signup');


Route::get('/product/autosearch', 'productController@autosearch' )->Name('productController.autosearch');


Route::get('/dashboard', 'dashboardController@dashboard' )->Name('dashboardController.dashboard');

Route::get('/logout', 'authenticationController@logout' )->Name('authenticationController.logout');




//Route::get('/db', 'testController@dbTest');
Route::get('/db', 'productController@autosearch');



//assignment routes
Route::resource('/a_pos' , 'AProductController');


//
Route::post('/a_cart' , 'AProductController@addtocart')->name('AProductController.addtocart');


Route::get('/a_cart' , function(){
	return 'hellow';
});


Route::post('/a_cart_delete/{cart_id}/{user_id}' , 'AProductController@a_cart_delete')->name('AProductController.a_cart_delete');

Route::post('/a_cart_update/{cart_id}/{user_id}/{qntity}' , 'AProductController@a_cart_update')->name('AProductController.a_cart_update');

