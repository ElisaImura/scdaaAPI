<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class PasswordResetController extends Controller
{
    // Enviar enlace de recuperación
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker()->sendResetLink(
            ['email' => $request->email]
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Correo enviado con el enlace de recuperación.'])
            : response()->json(['message' => 'Error al enviar el correo.'], 400);
    }

    // Reset de contraseña con token recibido por correo
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'uss_clave' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker()->reset(
            [
                'email' => $request->email,
                'password' => $request->uss_clave,
                'password_confirmation' => $request->uss_clave_confirmation,
                'token' => $request->token,
            ],
            function ($user, $uss_clave) {
                $user->forceFill([
                    'uss_clave' => Hash::make($uss_clave),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Contraseña actualizada correctamente.'])
            : response()->json(['message' => 'Error al restablecer la contraseña.'], 400);
    }

    // Cambiar contraseña dentro de la app (usuario logueado)
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->uss_clave)) {
            return response()->json(['message' => 'La contraseña actual no es correcta.'], 403);
        }

        $user->uss_clave = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Contraseña actualizada correctamente.']);
    }
}
