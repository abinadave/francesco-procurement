<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\PurchaseOrder as PurchaseOrder;
use App\PoItem as PoItem;
use App\QuotationForm as QuotationForm;

class PurchaseOrderController extends Controller
{
    public function fetchAll(){
        $purchaseOrders = PurchaseOrder::orderBy('id','desc')->get();
        $poItems = PoItem::all();
        return response()->json([
            'purchase_orders' => $purchaseOrders,
            'po_items'        => $poItems
        ]);
    }
    public function createPo(Request $request){
    	$quotationForm = $request->input('quotation_form');
        $approvalDate = $request->input('approval_date');
    	$items = $request->input('items');
    	$po = new PurchaseOrder;
    	$po->supplier_id = $quotationForm['supplier_id'];
    	$po->quotation_form_id = $quotationForm['id'];
    	$po->pr_no = $quotationForm['request_form_id'];
    	$po->personnel = Auth::user()->name;
    	$po->datetime = $quotationForm['datetime'];
    	$po->save();
    	if ($po->id > 0) {
            $this->approveQuotation($quotationForm, $approvalDate);
    		$this->savePoItems($po, $items, $quotationForm);
    	}
    	return response()->json($po);
    }
    private function approveQuotation($quotationForm, $approvalDate){
        QuotationForm::where('id', $quotationForm['id'])
                     ->update([
                        'approved' => 1, 
                        'approval_date' => $approvalDate,
                        'approved_by' => Auth::user()->id
                    ]);
    }
    private function savePoItems($po, $items, $quotationForm){
    	foreach ($items as $item) {
    		$po_item = new PoItem;
    		$po_item->qty = $item['qty'];
    		$po_item->unit = $item['unit'];
    		$po_item->unit_price = $item['unit_price'];
    		$po_item->description = $item['description'];
    		$po_item->po_id = $po->id;
    		$po_item->request_form_id = $quotationForm['request_form_id'];
    		$po_item->quotation_form_id = $quotationForm['id'];
    		$po_item->save();
    	}
    }
}
