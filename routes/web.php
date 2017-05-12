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

	/* for all */
	Route::post('get_quotation_forms_by_pr_no', 'QuotationFormController@fetchFormsAndItemsByPrNo');

	/* engineer basic route */
	Route::post('requisition', 'RequestFormController@submitNewRequest');
	Route::post('request_item', 'RequestItemController@saveItems');
	Route::get('get_my_requisitions', 'RequestFormController@fetchMyRequisitions');
	Route::post('get_request_items', 'RequestItemController@fetchMyItems');

	/* purchase officier */
	Route::get('request_forms_and_items', 'UserController@fetchRequestFormsAndItems');
	Route::post('get_request_items_by_form', 'RequestItemController@fetchItemsByFormId');
	Route::post('quotation_form', 'QuotationFormController@saveQuotation');
	Route::post('quotation_item', 'QuotationItemController@saveQuoteItems');
	Route::get('my_quotation_forms', 'QuotationFormController@fetchMyQuotations');
	Route::get('quotations_all', 'QuotationFormController@fetchAllwithItems');

	/* finance officer */
	Route::post('approve_pr', 'RequestFormController@approveForm');
});