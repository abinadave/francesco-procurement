<?php
date_default_timezone_set('Asia/Manila');
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

Route::get('/', 'HomeController@index')->name('home');

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
	Route::get('pr_estimated_costs', 'RequestFormController@fetchEstimatedCosts'); 
	Route::post('requisition_filter_by_house_model', 'HouseModelController@filterPrByHouseModel');
	Route::get('my_notification', 'NotificationController@fetchmynotifs');
	Route::post('read_notification', 'ReadNotificationController@markasreadselectednotif');
	Route::get('read_notification', 'ReadNotificationController@fetchmyreadednotif');

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
	Route::post('cancelation_of_quotation', 'QuotationFormController@cancelationOfQuotation');
	Route::get('po', function(){
		echo "Good";
	});

	/* finance officer and procurement officer */
	Route::post('approve_pr', 'RequestFormController@approveForm');
	Route::post('get_pr_form_by_id', 'RequestFormController@getFormById');
	Route::post('purchase_order', 'PurchaseOrderController@createPo');
	Route::get('po', 'PurchaseOrderController@fetchAll');
	Route::get('request_form_items', 'RequestFormController@fetchAllRequest');
	Route::get('quotations', 'QuotationFormController@fetchAllwithItems');
	Route::post('po_list_filter', 'PurchaseOrderController@filterlistby');
	Route::post('quotation_approved_or_not', 'QuotationFormController@filterbyapprovedornot');
	/* everyone */
	Route::post('opened_request', 'OpenedRequestController@validateAndInsert');
	Route::get('opened_request', 'OpenedRequestController@fetchall');
	Route::get('approved_pr', 'RequestFormController@approvedPr');
});

Route::get('find_supplier/{id}', function($id){
	$supplier = \App\Supplier::where('id', $id)->first();
	print_r($supplier->name);
});