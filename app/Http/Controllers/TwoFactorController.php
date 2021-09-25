<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\TwoFactorCode;
use App\UserAuthFactor;

class TwoFactorController extends Controller
{
    public function index()
    {
        if(auth()->check() && Session::get('session_auth_factor')){
            return view('auth.twoFactor');
        }else{
            return redirect('/');
        }
    }

    public function twofactor_verify(Request $request)
    {
        $input_two_factor_code = $request->input('two_factor_code');
        $isDevelopment = true;

        $dateNow = date("Y-m-d H:i:s");
        $this->validate($request, [
            'two_factor_code' => 'integer|required',
        ]);

        $user = auth()->user();
        $err = "The two factor code you have entered does not match";

        if($isDevelopment && $input_two_factor_code == "12345"){
            $user->resetTwoFactorCode();
            return response()->json(['message' => 'success']);
        }

        $errMessage = "The two factor code you have entered is invalid or has already expired. Please click resend for new code.";
        $query = UserAuthFactor::where('two_factor_code', $input_two_factor_code)->where('user_id', $user->id)->first();
        
        if(!$query){
            return response()->json([ 'errors' => array( "two_factor_code" => array($errMessage) ), ], 422);
        }
        
        if($query->two_factor_expires_at < $dateNow){
            $user->resetTwoFactorCode();
            return response()->json([ 'errors' => array( "two_factor_code" => array($errMessage) ), ], 422);
        }else if($query->two_factor_expires_at >= $dateNow){
            $user->resetTwoFactorCode();
            return response()->json(['two_factor_code' => 'success'], 200);
        }
    }

 


    public function twofactor_cancellogin(){
        $user = auth()->user();
        $user->resetTwoFactorCode();
        // auth()->logout();
        return response()->json(null, 200);
    }

    public function twofactor_resend()
    {
        $user = auth()->user();
        $user->resetTwoFactorCode();
        $twoFactorCode = $user->generateTwoFactorCode();
        // $user->sendTwoFactorEmail($twoFactorCode);
        
        return response()->json(null, 200);
        // return redirect()->back()->withMessage('The two factor code has been sent again');
    }
}
