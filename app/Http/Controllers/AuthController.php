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
            'uss_email'=> 'required|email|exists:users,uss_email',
            'uss_clave' => 'required'
        ]);
    
        $user = User::where('uss_email', $request->uss_email)->first();
    
        if (!$user || !Hash::check($request->uss_clave, $user->uss_clave)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }
    
        $token = $user->createToken($user->uss_email);
    
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
