<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\RequestForm as RequestForm;

class RequestFormController extends Controller
{
    public function submitNewRequest(Request $request){
    	$this->validate($request, [
            'location' => 'required',
            'block_no' => 'required|numeric',
            'house_model' => 'required'
        ]);
    	return $this->saveRequest($request);
    }
    public function saveRequest($request){
    	$requestForm = new RequestForm;
    	$requestForm->requested_by = Auth::user()->id;
    	$requestForm->location = $request->input('location');
    	$requestForm->block_no = $request->input('block_no');
    	$requestForm->house_model = $request->input('house_model');
    	$requestForm->datetime = $request->input('datetime');
    	$requestForm->checked_by = $request->input('checked_by');
    	$requestForm->save();
    	return response()->json($requestForm);
    }
}
