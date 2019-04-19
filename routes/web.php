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


Route::post('/a_cart_reset/{user_id}' , 'AProductController@a_cart_reset')->name('AProductController.a_cart_reset');



Route::post('/a_cart_show/{user_id}' , 'AProductController@a_cart_show')->name('AProductController.a_cart_show');



Route::post('/a_cart_order' , 'AProductController@a_cart_order')->name('AProductController.a_cart_order');



Route::get('/add_user' , 'userController@addUser')->name('userController.addUser');

Route::post('/add_user' , 'userController@addUserPost')->name('userController.addUser');


Route::get('/add_factory' , 'userController@add_factory')->name('userController.add_factory');

Route::post('/add_factory' , 'userController@add_factoryPost')->name('userController.add_factory');




Route::get('/ship_req_india' , 'userController@ship_req_india')->name('userController.ship_req_india');



Route::get('/ship_req_bd' , 'userController@ship_req_bd')->name('userController.ship_req_bd');



Route::post('/ship_req_bd' , 'userController@ship_req_bd_post')->name('userController.ship_req_bd');



Route::post('/a_shipment_reset/{uid}' , 'userController@a_shipment_reset')->name('userController.a_shipment_reset');



Route::post('/a_shipment_request/{uid}' , 'userController@a_shipment_request')->name('userController.a_shipment_request');



Route::post('/a_shipment_details/{ship_id}' , 'userController@a_shipment_details')->name('userController.a_shipment_details');



Route::post('/ship_accept/{ship_id}/{admin_id}' , 'userController@ship_accept')->name('userController.ship_accept');
Route::post('/ship_reject/{ship_id}/{admin_id}' , 'userController@ship_reject')->name('userController.ship_reject');







Route::get('/add_raw_materials' , 'userController@add_raw_materials')->name('userController.add_raw_materials');


