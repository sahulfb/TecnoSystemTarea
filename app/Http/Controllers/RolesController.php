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
    public function index()
    {
        if (Auth::guard('sanctum')->user()) {
            $response = response()->json(['roles' => Role::all()]);
        } else {
            $response = response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::guard('sanctum')->user()) {
            //crear rol
            $admin = Role::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description
            ]);

            $response = response()->json([
                'message' => 'Rol creado'
            ]);
        } else {
            $response = response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function asignar(int $rol, int $user)
    {
        $rol = Role::find($rol);
        $user = User::find($user);
        if ($rol && $user) {
            $user->addRole($rol);
            $response = response()->json([
                'message' => 'Rol asignado'
            ]);
        } else {
            $response = response()->json([
                'message' => 'invalid'
            ]);
        }

        return $response;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (Auth::guard('sanctum')->user()) {
            $data['name'] = $request['name'];
            $data['display_name'] = $request['display_name'];
            $data['description'] = $request['description'];
            Role::find($id)->update($data);
            $rol = Role::find($id);
            return response()->json([
                'message' => 'Rol actualizado',
            ]);
        } else {
            $response = response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $rol = Role::find($id)->delete();
            return response()->json(["deleted" => $rol], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
