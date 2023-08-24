<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::guard('sanctum')->user()) {
            $response = response()->json(['users' => User::all()]);
        } else {
            $response = response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        return $response;
    }

    public function create(Request $request)
    {
        $response = [];
        if (Auth::guard('sanctum')->user()) {
            $user = Auth::user();
            if ($user->id === 1 || $user->id === 2) {
                $data = $request->all();
                //crear usuario
                $user = User::create([
                    'name' => 'administrador',
                    'email' => $data['email'],
                    'password' => bcrypt($data['password'])
                ]);

                $response = response()->json([
                    'message' => 'Usuario creado'
                ]);
            } else {
                $response = response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
        }

        return $response;
    }

    public function update(Request $request)
    {
        $response = [];
        if (Auth::guard('sanctum')->user()) {
            $user = Auth::user();
            if ($user->id === 1 || $user->id === 2) {
                $data = $request->all();
                //crear usuario
                $user = User::create([
                    'name' => 'administrador',
                    'email' => $data['email'],
                    'password' => bcrypt($data['password'])
                ]);

                $response = response()->json([
                    'message' => 'Usuario creado'
                ]);
            } else {
                $response = response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
        }

        return $response;
    }
}
