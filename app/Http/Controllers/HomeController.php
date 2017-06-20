<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->usertype == 'admin') {
            return view('admin');
        }elseif(Auth::user()->usertype == 'engineer'){
            return view('engineer');
        }elseif(Auth::user()->usertype === 'purchase-officer'){
            return view('purchase-officer');
        }elseif(Auth::user()->usertype === 'finance-officer'){
            return view('finance-officer');
        }elseif(Auth::user()->usertype === 'procurement-officer'){
            return view('procurement');
        }else {
            return view('/');  
        }
    }
}
