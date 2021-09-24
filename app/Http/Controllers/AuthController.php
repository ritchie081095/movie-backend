<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('username', 'password');

        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        try {
            if (!$token = auth()->setTTL(1440)->attempt($credentials)) {
                return response()->json([ 'errors' => array(
                    "username" => array("Invalid account"),
                ), ], 400);
            }else{

                if(auth()->user()->user_status != 1){
                    return response()->json([ 'errors' => array(
                        "username" => array("Invalid account"),
                    ), ], 400);
                }

                $request['token'] = $token;
                return $this->getuserinfo($request);
            }
        } catch (\Throwable $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
    }

    public function getuserinfo($request){
        $two_factor_auth = false;
        if(auth()->check() && ($two_factor_auth == true)){
        //     $user = auth()->user();
        //     $twoFactorCode = $user->generateTwoFactorCode();
        //     $user->sendTwoFactorEmail($twoFactorCode);
        }

        $user = auth()->user();

        return response()->json([
            'hasAuthFactor' => $two_factor_auth ?? false,
            'isAuthFactorPass' => $two_factor_auth ? false:true,
            'access_token' => $request->token,
            'user' => $user,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL()
            // 'expire_at_days' => env('PERSONAL_ACCESS_TOKEN_EXPIRY__DAYS', 2),
            // 'date_expire' => now()->addHours( env('PERSONAL_ACCESS_TOKEN_EXPIRY__DAYS', 2) ),
            // 'date_expire_test' => date('Y-m-d H:i:s', strtotime( now()->addHours( env('PERSONAL_ACCESS_TOKEN_EXPIRY__DAYS', PERSONAL_ACCESS_TOKEN_EXPIRY__DAYS) ) )),
        ]);
    }
    
    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:6|confirmed',
    //     ]);

    //     if($validator->fails()){
    //             return response()->json($validator->errors()->toJson(), 400);
    //     }

    //     $user = User::create([
    //         'name' => $request->get('name'),
    //         'email' => $request->get('email'),
    //         'password' => Hash::make($request->get('password')),
    //     ]);

    //     $token = Auth::user()->createToken('authToken')->accessToken;

    //     return response()->json(compact('user','token'),201);
    // }

    public function getuserdata(){
        // return app('filesystem');
        // \Storage::disk('public')->put('file.txt', 'Contents');
        // return public_path('storage');

        // return storage_path();

        $user = Auth::user();
        return response()->json([
            'user' => $user,
        ]);
    }
    
    public function logout(){
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
   
}
