<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index(){
        return view('reg');
    }

    public function register(Request $request){
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if($user->save()){
            return redirect()->route('confirmPhone', $user->name);
        }
        else{
            return back()->with('message', 'Registration Failed');
        }
    }

    public function confirmPhone(Request $request){
        return view('confirm');
    } 
}
