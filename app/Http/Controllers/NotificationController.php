<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notification as Notification;

class NotificationController extends Controller
{
    public function fetchmynotifs(){
    	$usertype = Auth::user()->usertype;
    	$notifications = Notification::where('for_position', $usertype)->orderBy('id','desc')->get();
    	return response()->json($notifications);
    }
}
