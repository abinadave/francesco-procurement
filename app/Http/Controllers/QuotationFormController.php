<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\QuotationForm as QuotationForm;
use App\QuotationItem as QuotationItem;
use App\Supplier as Supplier;
use App\Events\SomethingHappenCreateNotification;

class QuotationFormController extends Controller
{
    public function filterbyapprovedornot(Request $request){
        $selected = $request->input('selected');
        $qfs = array();
        $qfitems = array();
        if ($selected == 'approved' || $selected == 'not-approved') {
            $selected = ($selected == 'approved') ? 1 : 0;
            $qfs = QuotationForm::where('approved', $selected)->orderBy('approval_date','desc')->get();
            $qfitems = QuotationItem::whereIn('quotation_form_id', $qfs->pluck('id'))->get();
        }elseif($selected == 'all') {
            $qfs = QuotationForm::orderBy('id','desc')->get();
            $qfitems = QuotationItem::all();
        }
        return response()->json([
            'quotation_forms' => $qfs,
            'quotation_items' => $qfitems,
            'selected' => $selected
        ]);
    }
    public function fetchall(){
        return response()->json([
            'quotation_forms' => QuotationForm::orderBy('id','desc')->get(),
            'quotation_items' => QuotationItem::all()
        ]);
    }
    public function cancelationOfQuotation(Request $request){
        $quotationForm = $request->input('quotation_form');
        $count = QuotationForm::where('id', $quotationForm['id'])->count();
        $deleted = false;
        $deleletedQI = 0;
        if ($count) {
            $deleted = QuotationForm::where('id', $quotationForm['id'])->delete();
            $deleletedQI = QuotationItem::where('quotation_form_id', $quotationForm['id'])->delete();
        }
        return response()->json([
            'deleted'    => $deleted,
            'dleeted_qi' => $deleletedQI,
            'count'      => $count
        ]);
    }

    public function fetchFormsAndItemsByPrNo(Request $request){
        $requestForm = $request->input('request_form');
        $prNo = $requestForm['id'];
        $quotationForms = QuotationForm::where('request_form_id', $prNo)->get();
        $quotationItems = QuotationItem::where('request_form_id', $prNo)->get();
        return response()->json([
            'quotation_forms' => $quotationForms,
            'quotation_items' => $quotationItems
        ]);
    }
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
        $this->notifyOthers($request, $quotationForm);
        return response()->json($quotationForm);
    }
    protected function notifyOthers($request, $quotationForm){
        $supplier = Supplier::where('id', $quotationForm->supplier_id)->first();
        $prNo = $request->input('request_form_id');
        event(new SomethingHappenCreateNotification(
            'procurement-officer',
            Carbon::now(),
            'Quotation ['. $supplier->name .'] was created by ' . strtoupper(Auth::user()->usertype) . ' ' . strtoupper(Auth::user()->name) . ' with PR No. ' . $prNo . ', waiting for approval',
            Auth::user()->id
        ));
    }
}
