<?php

namespace App\Http\Controllers;

use App\Events\DataEvent;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Repositories\TaskRepositories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionsController extends Controller
{
    private $taskRepositories;
    public function __construct(TaskRepositories $taskRepositories)
    {
        $this->taskRepositories = $taskRepositories;
    }

    public function index()
    {
        if (Auth::guard('sanctum')->user()) {
            $response = $this->taskRepositories->all();
            return $response;
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
        //validar si esta vacio
        $porValidar = $this->taskRepositories->validar($request);
        if (isset($porValidar)) {
            return $porValidar;
        }
        //validar que no exista
        $exist = $this->taskRepositories->findExist('name', $request->name);
        if ($exist) {
            return response()->json([
                'message' => 'El permiso ya existe'
            ]);
        }
        $this->taskRepositories->create($request);
        DataEvent::dispatch('Nuevo permiso creado');
        return response()->json([
            'message' => 'Permiso creado'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        //validar si esta vacio
        $porValidar = $this->taskRepositories->validar($request);
        if (isset($porValidar)) {
            return $porValidar;
        }
        //validar que exista
        $exist = $this->taskRepositories->findExist('id', $id);

        if (!$exist) {
            return response()->json([
                'message' => 'El permiso no existe'
            ]);
        }

        $this->taskRepositories->updatePermiso($request, $id);
        return response()->json([
            'message' => 'Permiso actualizado'
        ]);
    }

    public function asignar(int $permiso, int $user)
    {
        $permiso = Permission::find($permiso);
        $user = User::find($user);

        //validar que exista
        $exist = $user->hasPermission([$permiso->name]);
        return $exist;
        if ($exist) {
            return response()->json([
                'message' => 'El usuario ya tiene ese permiso'
            ]);
        }

        if ($user && $permiso) {
            $user->givePermission($permiso);
            $response = response()->json([
                'message' => 'Permiso asignado'
            ]);
        } else {
            $response = response()->json([
                'message' => 'invalid'
            ]);
        }

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        //validar que exista
        $exist = $this->taskRepositories->findExist('id', $id);
        if (!$exist) {
            return response()->json([
                'message' => 'El permiso no existe'
            ]);
        }

        $this->taskRepositories->deletePermiso($id);
        return response()->json([
            'message' => 'Permiso eliminado'
        ]);
    }
}
