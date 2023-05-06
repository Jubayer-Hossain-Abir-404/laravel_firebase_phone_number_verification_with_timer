<?php

namespace App\Http\Controllers;

use App\Models\OtpVerify;
use App\Models\User;
use Carbon\Carbon;
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
            return redirect()->route('confirmPhone', $user->id);
        }
        else{
            return back()->with('message', 'Registration Failed');
        }
    }

    public function confirmPhone($id){
        return view('confirm', compact('id'));
    }
    
    
    public function storeConfirmedPhone(Request $request){
        $phone = $request->phone;
        $id = $request->id;

        $otp = new OtpVerify();
        $otp->verifiable_id = $id;

        $otp->phone = $phone;
        
        $otp->created_at = Carbon::now();
        

        if($otp->save()){
            $added_time = Carbon::now()->addMinute();
            $time = Carbon::parse($added_time)->isoFormat('MMM D, Y H:m:s');
            
            return response()->json(['time'=> $time]);
        }
    }
}
