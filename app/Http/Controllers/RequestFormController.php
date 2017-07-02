<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\RequestForm as RequestForm;
use App\RequestItem as RequestItem;
use App\QuotationItem as QuotationItem;
use App\ApprovedDate as ApprovedDate;
use DB;
class RequestFormController extends Controller
{
    public function fetchEstimatedCosts(){
        $totals = DB::select("SELECT request_form_id, SUM(qty * unit_price) AS ESTIMATED_COST FROM request_items GROUP BY request_form_id");
        return response()->json($totals);
    }

    public function approvedPr(){
        return response()->json([
            'approved_dates' => ApprovedDate::all()
        ]);
    }

    public function insertApprovalDate($form, $date){
        $approvedDate = new ApprovedDate;
        $approvedDate->date_of_approval = $date;
        $approvedDate->request_form_id = $form['id'];
        $approvedDate->approved_by = Auth::user()->id;
        $approvedDate->save();
    }
    public function approveForm(Request $request){
        $form = $request->input('request_form');
        $type = $request->input('type');
        $date = $request->input('date');
        $model = RequestForm::findOrFail($form['id']);
        $model->approved = $type;
        $rsUpdated = $model->save();
        if ($type) {
            $this->insertApprovalDate($form, $date);
        }
        return response()->json([
            'updated' => $rsUpdated,
            'model' => $model,
            'type' => $type
        ]);
    }
    public function fetchMyRequisitions(){
        $id = Auth::user()->id;
        $requestForms = RequestForm::where('requested_by', $id)->orderBy('id','asc')->get();
        return response()->json($requestForms);
    }
    public function submitNewRequest(Request $request){
    	$this->validate($request, [
            'location' => 'required',
            'block_no' => 'required|numeric',
            'charging' => 'required',
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
        $requestForm->charging = $request->input('charging');
    	$requestForm->datetime = $request->input('datetime');
    	$requestForm->checked_by = $request->input('checked_by');
    	$requestForm->save();
    	return response()->json($requestForm);
    }
    public function getFormById(Request $request){
        $quotationForm = $request->input('quotation_form');
        $pr_no = $quotationForm['request_form_id'];
        return response()->json([
            'request_form'  => RequestForm::findOrFail($pr_no),
            'request_items' => RequestItem::where('request_form_id', $pr_no)->get(),
            'quotation_items' => QuotationItem::where('request_form_id', $pr_no)
                                              ->where('quotation_form_id', $quotationForm['id'])->get()
        ]);
    }
    public function fetchAllRequest(){
        $requestForms = RequestForm::orderBy('id','desc')->get();
        $requestItems = RequestItem::orderBy('id','desc')->get();
        return response()->json([
            'request_forms' => $requestForms,
            'request_items' => $requestItems
        ]);
    }
}
