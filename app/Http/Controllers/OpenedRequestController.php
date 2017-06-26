<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\OpenedRequest as OpenedRequest;
class OpenedRequestController extends Controller
{
	public function fetchall(){
		return response()->json(OpenedRequest::where('user_id', Auth::user()->id)->get());
	}
    public function validateAndInsert(Request $request){
    	$form = $request->input('form');
    	$pr_no =  $form['id'];
    	$count = OpenedRequest::where('pr_no', $pr_no)->where('user_id', Auth::user()->id)->count();
    	$or = array();
    	if (!$count) {
    		$or = new OpenedRequest;
	    	$or->user_id = Auth::user()->id;
	    	$or->pr_no = $pr_no;
	    	$or->save();
	 	}
	 	return response()->json([
	 		'duplicate' => $count,
	 		'or' => $or
	 	]);
    }
}
