<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permisos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('permisos')->get();
        
        $users->each(function ($user) {
            if ($user->permisos->isEmpty()) {
                $user->message = "Este usuario no tiene permisos asignados.";
            }
        });

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'rol_id' => 'required|exists:roles,rol_id',
            'uss_nombre' => 'required|string|max:255',
            'uss_email' => 'required|email|unique:users,uss_email',
            'uss_clave' => 'required|string|min:6',
        ]);

        $user = User::create([
            'rol_id' => $request->rol_id,
            'uss_nombre' => $request->uss_nombre,
            'uss_email' => $request->uss_email,
            'uss_clave' => Hash::make($request->uss_clave),
        ]);

        if ($user->rol_id == 1) {
            $user->permisos()->sync(Permisos::pluck('perm_id'));
            return response()->json(['message' => 'Usuario creado con éxito','user' => $user->load('permisos')], 201);
        }

        return response()->json(['message' => 'Usuario creado con éxito', 'user' => $user], 201);
    }

    public function show(string $id)
    {
        $user = User::with('permisos')->findOrFail($id);
        
        return response()->json($user);
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
    
        $request->validate([
            'rol_id' => 'sometimes|exists:roles,rol_id',
            'uss_nombre' => 'sometimes|string|max:255',
            'uss_email' => ['sometimes', 'email', Rule::unique('users', 'uss_email')->ignore($id, 'uss_id')],
            'uss_clave' => 'sometimes|string|min:6',
        ]);
    
        // Solo actualizar los campos enviados en la request
        $data = $request->only(['rol_id', 'uss_nombre', 'uss_email']);
    
        // Si se envió una clave nueva, la encriptamos antes de actualizar
        if ($request->filled('uss_clave')) {
            $data['uss_clave'] = Hash::make($request->uss_clave);
        }
    
        // Ahora sí, actualizamos el usuario correctamente
        $user->update($data);
    
        // Si el usuario tiene rol de administrador (id=1), asignamos todos los permisos
        if ($user->rol_id == 1) {
            $user->permisos()->sync(Permisos::pluck('perm_id'));
        }
    
        return response()->json([
            'message' => 'Usuario editado con éxito',
            'user' => $user->load('permisos')
        ], 200);
    }    

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Usuario eliminado con éxito']);
    }


    //this shit doesn't work yet
    public function asignarPermisos(Request $request, string $id)
    {
        $admin = auth()->user();

        if (!$admin) {
            return response()->json(['message' => 'No se encontró un usuario autenticado.'], 401);
        }
    
        if ($admin->rol_id !== 1) {
            return response()->json(['message' => 'No tienes permisos para realizar esta acción.'], 403);
        }

        $user = User::findOrFail($id);

        if ($user->rol_id == 1) {
            $user->permisos()->sync(Permisos::pluck('perm_id'));
            return response()->json([
                'message' => 'Usuario con rol de administrador, se asignaron todos los permisos.',
                'user' => $user->load('permisos'),
            ]);
        }

        $request->validate([
            'permisos' => 'required|array',
            'permisos.*' => 'exists:permisos,perm_id',
        ]);

        $user->permisos()->sync($request->permisos);

        return response()->json([
            'message' => 'Permisos asignados correctamente',
            'user' => $user->load('permisos'),
        ]);
    }
}
