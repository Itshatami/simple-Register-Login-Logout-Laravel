<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
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

    
}
