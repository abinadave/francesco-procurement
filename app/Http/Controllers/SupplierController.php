<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supplier as Supplier;

class SupplierController extends Controller
{
    public function delete($id){
        $supplier = Supplier::findOrFail($id);
        $rs = $supplier->delete(); 
        return response()->json([
            'deleted' => $rs
        ]);
    }
	public function fetchAll(){
		$resp = Supplier::orderBy('name','asc')->get();
		return $resp;
	}
    public function validateAndSave(Request $request){
    	$this->validate($request, [
            'name' => 'required|unique:suppliers|max:100',
            'address' => 'required|max:255'
        ]);
       	return $this->saveSupplier($request);
    }
    public function saveSupplier($request){
    	$supplier = new Supplier;
    	$supplier->name = $request->input('name');
    	$supplier->address = $request->input('address');
    	$supplier->contact = $request->input('contact');
    	$supplier->save();
    	return response()->json($supplier);
    }
}
