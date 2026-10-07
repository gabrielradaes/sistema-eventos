<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Crear un nuevo usuario
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403, 'Acceso denegado.');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return back();
    }

    // Actualizar un usuario existente
    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') abort(403, 'Acceso denegado.');

        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'role' => 'required|string'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        
        // Solo actualizar la contraseña si escribieron una nueva
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        return back();
    }

    // Borrar un usuario
    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') abort(403, 'Acceso denegado.');
        
        // Evitar que el admin se borre a sí mismo por accidente
        if (auth()->id() == $id) {
            return back(); 
        }

        User::findOrFail($id)->delete();
        return back();
    }
}