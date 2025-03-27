<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email'=> 'required|email|exists:users,email',
            'uss_clave' => 'required'
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user || !Hash::check($request->uss_clave, $user->uss_clave)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }
    
        $token = $user->createToken($user->email);
    
        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken
        ]);
    }

    public function logout(Request $request){    
        $request->user()->tokens()->delete();
    
        return response()->json([
            'message' => "You are logged out."
        ]);
    }
}
