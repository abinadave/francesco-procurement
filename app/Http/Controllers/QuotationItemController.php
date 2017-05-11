<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QuotationItem as QuotationItem;

class QuotationItemController extends Controller
{
    public function saveQuoteItems(Request $request){
    	$items = $request->input('items');
    	foreach ($items as $key => $item) {
    		$quotationItem = new QuotationItem;
    		$quotationItem->qty = $item['qty'];
    		$quotationItem->unit = $item['unit'];
    		$quotationItem->description = $item['description'];
    		$quotationItem->unit_price = $item['unit_price'];
    		$quotationItem->request_form_id = $request->input('request_form_id');
    		$quotationItem->quotation_form_id = $request->input('quotation_form_id');
    		$quotationItem->save();
    	}
    }
}
