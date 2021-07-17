<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('home');
});
Route::get('/about', function () {
    return view('about');
  })->name('about_it');
Route::get('/home/products',['as'=>'all_products','uses'=>'App\Http\Controllers\ProductsController@allProduct']);
Route::get('/home/products/{category_id?}',['as'=>'products_of_category', 'uses'=>'App\Http\Controllers\ProductsController@categoryProduct']);
Route::get('/home/products/product/{id}',['as'=>'product_by_id', 'uses'=>'App\Http\Controllers\ProductsController@productById']);
Route::post('/home/cart/add',['as'=>'add_to_cart','uses'=>'App\Http\Controllers\CartController@add']);
Route::post('/home/cart/delete', ['as'=>'delete_from_cart','uses'=>'App\Http\Controllers\CartController@delete']);
Route::post('/home/cart/update,{crement}',['as'=>'change_cart','uses'=>'App\Http\Controllers\CartController@crement']);
Route::post('/home/cart/clear',['as'=>'clear_cart', 'uses'=>'App\Http\Controllers\CartController@clear']);
Route::get('/home/cart',['as'=>'all_of_cart','uses'=>'App\Http\Controllers\CartController@all']);
Route::post('/home/cart/submit',['as'=>'submit_order', 'uses'=>'App\Http\Controllers\CartController@submit']);
// Route::get('/', ['as'=>'/', 'uses'=>'LoginController@getLogin']);
