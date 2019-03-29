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

Route::get('/login', 'authenticationController@login' )->Name('authentication.login');

Route::post('/login', 'authenticationController@loginCheck' )->Name('authentication.login');


Route::get('/dashboard', 'dashboardController@dashboard' )->Name('dashboardController.dashboard');

Route::get('/logout', 'authenticationController@logout' )->Name('authenticationController.logout');


Route::get('/db', 'testController@dbTest');

