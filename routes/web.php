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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

 Route::group(['middleware' => 'auth'], function () {
    Route::post('supplier', 'SupplierController@validateAndSave');
	Route::get('supplier', 'SupplierController@fetchAll');
	Route::delete('supplier/{id}', 'SupplierController@delete');
	Route::post('create_user', 'UserController@validateAndSave');
	Route::get('user', 'UserController@fetchAll');
	Route::delete('user/{id}', 'UserController@delete');
	Route::get('user_details', 'UserController@getUserDetails');
	Route::post('house_model', 'HouseModelController@insert');
	Route::get('house_model', 'HouseModelController@fetchAll');
	Route::put('house_model', 'HouseModelController@update');

	/* engineer basic route */
	Route::post('requisition', 'RequestFormController@submitNewRequest');
});