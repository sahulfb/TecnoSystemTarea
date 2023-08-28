<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Saludos,' . $user->name,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'data' => $user, 'access_token' => $token, 'token_type' => 'Bearer'
        ]);
    }

    public function getDatosToken(Request $request)
    {
        return $request->user();
    }

    public function validarToken()
    {
        //Carlos
        $valid = Auth::guard('sanctum')->user();
        $status = !!$valid ? 200 : 401;

        return response()->json(['valid' => $valid], $status);



        if (Auth::guard('sanctum')->user()) {
            return response()->json([
                'valid' => true
            ], 200);
        } else {
            return response()->json([
                'valid' => false
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        //eliminar el token actual
        $request->user()->currentAccessToken()->delete();

        $param1 = null;
        $param2 = 204;

        return response()->json($param1, $param2);
    }
}
