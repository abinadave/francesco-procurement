<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\ReadNotification;

class ReadNotificationController extends Controller
{
	public function fetchmyreadednotif(){
		$read_notifications = ReadNotification::where('readed_by', Auth::user()->id)->get();
		return response()->json($read_notifications);
	}
    public function markasreadselectednotif(Request $request){
    	$ids = $request->input('ids');
    	$saved = array();
    	foreach ($ids as $key => $nid) {
    		$readed = new ReadNotification;
    		$readed->notif_id = $nid;
    		$readed->readed_by = Auth::user()->id;
    		$readed->datetime_readed = Carbon::now();
    		$readed->save();
    		array_push($saved, $readed);
    	}
    	return response()->json($saved);
    }
}
