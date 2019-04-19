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



///////////////////////assignment routes//////////////////////////////
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


Route::post('/delete_it_ship/{ship_id}/{admin_id}' , 'AProductController@delete_it_ship')->name('AProductController.delete_it_ship');


Route::post('/update_it_ship/{ship_id}/{admin_id}/{qntity}' , 'AProductController@update_it_ship')->name('AProductController.update_it_ship');



Route::post('/a_shipment_request/{uid}' , 'userController@a_shipment_request')->name('userController.a_shipment_request');



Route::post('/a_shipment_details/{ship_id}' , 'userController@a_shipment_details')->name('userController.a_shipment_details');




Route::post('/ship_accept/{ship_id}/{admin_id}' , 'userController@ship_accept')->name('userController.ship_accept');


Route::post('/ship_reject/{ship_id}/{admin_id}' , 'userController@ship_reject')->name('userController.ship_reject');



Route::get('/add_raw_materials' , 'userController@add_raw_materials')->name('userController.add_raw_materials');


Route::post('/add_raw_materials' , 'userController@add_raw_materialsPost')->name('userController.add_raw_materials');


Route::post('/req_count' , 'AProductController@req_countPost')->name('AProductController.req_count');


Route::get('/money_transfer' , 'accountController@money_transfer')->name('accountController.money_transfer');


Route::post('/money_transfer' , 'accountController@money_transferPost')->name('accountController.money_transfer');


Route::get('/money_transfer_status' , 'accountController@money_transfer_status')->name('accountController.money_transfer_status');


Route::get('/shipment_status' , 'accountController@shipment_status')->name('accountController.shipment_status');


Route::get('/sales_report' , 'accountController@sales_report')->name('accountController.sales_report');


Route::get('/sales_report_yearly' , 'accountController@sales_report_yearly')->name('accountController.sales_report_yearly');


Route::get('/money_transfer_request' , 'accountController@money_transfer_request')->name('accountController.money_transfer_request');



Route::post('/money_transfer_request' , 'accountController@money_transfer_requestPost')->name('accountController.money_transfer_request');



Route::post('/money_accept/{id}/{admin_id}' , 'accountController@money_accept')->name('accountController.money_accept');




