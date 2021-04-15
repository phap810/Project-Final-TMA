<?php
use App\Models\HomeController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::group(['namespace' => 'Api', 'middleware' => ['cors']], function () {

    /*
    |--------------------------------------------------------------------------
    | Open Routes
    |--------------------------------------------------------------------------
    */

 //   Route::post('users/login', 'UserController@login');

    /*
    |--------------------------------------------------------------------------
    | Protected Routes, Authorization Required
    |--------------------------------------------------------------------------
    */

//     Route::group(['middleware' => ['auth']], function () {

//         Route::post('users/logout', 'UserController@logout');
//         Route::get('product', 'ProductController@getProductList');

//     });
// });
Route::namespace('Api')->group(function(){
    //Users
    Route::get('user', 'UserController@search')->name('user.search');
    Route::post('user', 'UserController@store')->name('user.store');
    Route::get('user/{id}', 'UserController@show')->name('user.show');
    Route::put('user/{id}', 'UserController@update')->name('user.update');
    Route::delete('user/{id}', 'UserController@destroy')->name('user.destroy');

    Route::post('login', 'UserController@login')->name('user.login');

    //Category
    Route::get('category', 'CategoryController@search')->name('category.search');
    Route::post('category', 'CategoryController@store')->name('category.store');
    Route::get('category/{id}', 'CategoryController@show')->name('category.show');
    Route::put('category/{id}', 'CategoryController@update')->name('category.update');

    //Supplier
    Route::get('supplier', 'SupplierController@search')->name('supplier.search');
    Route::post('supplier', 'SupplierController@store')->name('supplier.store');
    Route::get('supplier/{id}', 'SupplierController@show')->name('supplier.show');
    Route::put('supplier/{id}', 'SupplierController@update')->name('supplier.update');

    //Product
    Route::get('product', 'ProductController@search')->name('product.search');
    Route::post('product', 'ProductController@store')->name('product.store');
    Route::get('product/{id}', 'ProductController@show')->name('product.show');
    Route::put('product/{id}', 'ProductController@update')->name('product.update');
    Route::delete('product/{id}', 'ProductController@destroy')->name('product.destroy');

    //Home
    Route::get('home-category/{id}', 'ProductController@category')->name('product.category');

    //Customer
    Route::get('customer', 'CustomerController@search')->name('customer.search');
    Route::get('customer/{id}', 'CustomerController@show')->name('customer.show');
    Route::delete('customer/{id}', 'CustomerController@destroy')->name('customer.destroy');

    //Bill
    Route::post('bill', 'BillController@store')->name('bill.store');
    //Route::get('bill', 'BillController@search')->name('bill.search');
    //Route::get('bill/{id}', 'BillController@show')->name('bill.show');
    //Route::put('bill/{id}', 'BillController@update')->name('bill.update');
    //Route::delete('bill/{id}', 'BillController@destroy')->name('bill.destroy');

    //Cart
    Route::post('add-to-cart', 'CartController@add')->name('cart.add');
    Route::get('show-cart', 'CartController@show')->name('cart.show');
    Route::get('update-cart/{id}', 'CartController@update')->name('cart.update');
    Route::get('remove/{id}', 'CartController@remove')->name('cart.remove');
    Route::get('clear', 'CartController@clear')->name('cart.clear');
    //Route::get('view-cart', 'CartController@view')->name('cart.view');
});