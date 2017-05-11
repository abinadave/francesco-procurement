<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\User as User;
use App\RequestForm as RequestForm;
use App\RequestItem as RequestItem;

class UserController extends Controller
{
    public function fetchRequestFormsAndItems(){
        $forms = RequestForm::orderBy('id','asc')->get();
        $items = RequestItem::orderBy('id','asc')->get();
        return response()->json([
            'request_forms' => $forms,
            'request_items' => $items
        ]);
    }
    public function getUserDetails(){
        return response()->json(Auth::user());
    }
    public function delete(){
        echo "hit in controller";
    }
	public function fetchAll(){
		$resp = User::where('usertype', '<>', 'admin')->orderBy('id','ASC')->get();
		return response()->json($resp);
	}
    public function validateAndSave(Request $request){
    	$this->validate($request, [
            'name' => 'required|unique:users|max:100',
            'email' => 'required|unique:users|max:100|min:3',
            'password' => 'required|confirmed',
            'usertype' => 'required'
        ]);
       	return $this->saveSupplier($request);
    }
    public function saveSupplier($request){
    	$user = new User;
    	$user->name = $request->input('name');
    	$user->email = $request->input('email');
    	$user->password = Hash::make($request->input('password'));
    	$user->usertype = $request->input('usertype');
    	$user->save();
    	return response()->json($user);
    }
}
