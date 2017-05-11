<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\QuotationForm as QuotationForm;
use App\QuotationItem as QuotationItem;

class QuotationFormController extends Controller
{
    public function fetchAllwithItems(){
        return response()->json([
            'quotation_items' => QuotationItem::all(),
            'quotation_forms' => QuotationForm::orderBy('id','desc')->get()
        ]);
    }
    public function fetchMyQuotations(){
        $quotationForms = QuotationForm::where('purchase_officer_id', Auth::user()->id)->get();
        $quotationItems = QuotationItem::whereIn('quotation_form_id',
                          $quotationForms->pluck('id'))->get();
        return response()->json([
            'quotation_forms' => $quotationForms,
            'quotation_items' => $quotationItems
        ]);
    }
    public function saveQuotation(Request $request){
    	$this->validate($request, [
            'supplier_id' => 'required',
            'canvass_by' => 'required',
            'canvass_date' => 'required|date'
        ]);
        $request_form_id = $request->input('request_form_id');
        $supplier_id = $request->input('supplier_id');
        $datetime = $request->input('datetime');
        $quotationForm = new QuotationForm;
        $quotationForm->supplier_id = $request->input('supplier_id');
        $quotationForm->request_form_id = $request->input('request_form_id');
        $quotationForm->datetime = $request->input('datetime');
        $quotationForm->canvass_by = $request->input('canvass_by');
        $quotationForm->canvass_date = $request->input('canvass_date');
        $quotationForm->datetime = $request->input('datetime');
        $quotationForm->purchase_officer_id = Auth::user()->id;
        $quotationForm->save();
        return response()->json($quotationForm);
    }
}
