<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\RequestForm as RequestForm;
use App\RequestItem as RequestItem;
use App\HouseModel as HouseModel;
class HouseModelController extends Controller
{
    public function filterPrByHouseModel(Request $request){
        $house_model = $request->input('house_model');
        $request_forms = RequestForm::where('house_model', $house_model)->get();
        $pluckedIds = $request_forms->pluck('id');
        $request_items = RequestItem::whereIn('request_form_id', $pluckedIds)->get();
        return response()->json([
            'request_forms' => $request_forms,
            'request_items' => $request_items
        ]);
    }
    public function update(Request $request){
        $id = $request->input('id');
        $house = HouseModel::findOrFail($id);
        $house->model = $request->input('model');
        $rsUpdated =$house->save();
        return response()->json([
            'updated' => $rsUpdated
        ]);
    }
	public function fetchAll(){
		$resp = HouseModel::orderBy('model','asc')->get();
		return response()->json($resp);
	}
    public function insert(Request $request){
    	$this->validate($request, [
            'model' => 'required|unique:house_models|max:100'
        ]);
        $hm = new HouseModel;
        $hm->model = $request->input('model');
        $hm->created_by = Auth::user()->id;
        $hm->save();
        return response()->json($hm);
    }
}
