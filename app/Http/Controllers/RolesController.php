<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function asignar(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $rol = Role::findOrFail($request->role_id);
        $user = User::findOrFail($request->user_id);

        if (!$user->hasRole($rol->name)) {
            $user->addRole($rol);
        }

        return response()->json([
            'message' => 'Rol asignado'
        ]);
    }

    public function index()
    {
        return  response()->json(['roles' => Role::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'display_name' => 'required',
            'description' => 'required'
        ]);

        //crear rol
        $admin = Role::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'Rol creado'
        ]);
    }

    /**
     * Display the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        $request->validate([
            'name' => 'except'
        ]);

        $rol = Role::findOrFail($id);

        // guardar producto
        $rol->update($request->all());
        return response()->json([
            'message' => 'Rol actualizado'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $rol = Role::find($id)->delete();
            return response()->json(["deleted" => $rol], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
