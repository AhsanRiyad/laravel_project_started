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




/*Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');

*/

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/', function () {
    return view('welcome');
});




Route::get('/ff', function(){
	return 'hellow world';
});

Route::post('/addBazar', 'mealController@addBazar');
Route::post('/addMeal', 'mealController@addMeal');


Route::get('/test', 'mealController@addBazar');
