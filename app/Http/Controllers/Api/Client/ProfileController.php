<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'id'      => $user->id,
            'name'    => $user->name,
            'email'   => $user->email,
            'phone'   => $user->phone,
            'address' => $user->address,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'phone'            => 'nullable|string|max:20',
            'address'          => 'nullable|string|max:500',
            'current_password' => 'nullable|string',
            'password'         => 'nullable|string|min:8|confirmed',
        ], [
            'name.required'   => 'El nombre es obligatorio.',
            'password.min'    => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        if (!empty($data['current_password'])) {
            if (!Hash::check($data['current_password'], $user->password)) {
                return response()->json(['message' => 'La contraseña actual es incorrecta.'], 422);
            }
            $user->password = Hash::make($data['password']);
        }

        $user->name    = $data['name'];
        $user->phone   = $data['phone'] ?? $user->phone;
        $user->address = $data['address'] ?? $user->address;
        $user->save();

        return response()->json([
            'message' => 'Perfil actualizado correctamente.',
            'user'    => [
                'id'      => $user->id,
                'name'    => $user->name,
                'email'   => $user->email,
                'phone'   => $user->phone,
                'address' => $user->address,
            ],
        ]);
    }
}
