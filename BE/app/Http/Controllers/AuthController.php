<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /*
    | send this parameters to this method
    | name
    | email
    | password
    | c_password
    */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:5'],
            'c_password' => ['required', 'same:password'],
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, $validator->messages()]);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);
        if ($user) {
            $token = $user->createToken('myToken')->accessToken;
        } else {
            return response()->json(['status' => false, 'message' => 'register fails']);
        }
        return response()->json([
            'status' => true,
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:5'],
        ]);
        if($validator->fails()){
            return response()->json(['status'=>false , $validator->messages()]);
        }
        $user = User::where('email' , $request->email)->first();
        if(!$user){
            return response()->json(['status'=>false , 'message'=>'user does not found']);
        }else if(!Hash::check($request->password , $user->password)){
            return response()->json(['status'=>false , 'message'=>'password incorrect']);
        }
        $token = $user->createToken('myToken')->accessToken;
        return response()->json([
            'status'=>true,
            'user'=>$user,
            'token'=>$token
        ]);
    }
}
