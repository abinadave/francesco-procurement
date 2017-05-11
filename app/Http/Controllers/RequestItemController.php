<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RequestItem as RequestItem;

class RequestItemController extends Controller
{
    public function fetchItemsByFormId(Request $request){
        $id = $request->input('request_form_id');
        $items = RequestItem::where('request_form_id', $id)->get();
        return response()->json($items);
    }
    public function fetchMyItems(Request $request){
        $ids = $request->input('ids');
        $requestItems = RequestItem::whereIn('request_form_id', $ids)->get();
        return response()->json($requestItems);
    }
    public function saveItems(Request $request){
    	$items = $request->input('items');
    	$requestForm = $request->input('request_form');
    	$savedItems = array();
    	foreach ($items as $item) {
    		$requestItem = new RequestItem;
    		$requestItem->request_form_id = $requestForm['id'];
    		$requestItem->qty = $item['qty'];
    		$requestItem->unit = $item['unit'];
    		$requestItem->description = $item['description'];
    		$requestItem->unit_price = $item['unit_price'];
    		$requestItem->save();
    		array_push($savedItems, $requestItem);
    	}
    	return response()->json($savedItems);
    }
}
