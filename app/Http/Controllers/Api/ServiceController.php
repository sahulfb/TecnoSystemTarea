<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DummyApiServices;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    protected $dummyApiService;

    public function __construct(DummyApiServices $dummyApiService)
    {
        $this->dummyApiService = $dummyApiService;
    }

    public function index()
    {
        $users = $this->dummyApiService->getUsers();

        return $users;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($id)
    {
        return $id;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = $this->dummyApiService->getUserId($id);
        return $post;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        $dato = $request->dato;
        $valor = $request->valor;
        $user = $this->dummyApiService->updateUser($id, $dato, $valor);
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Auth::guard('sanctum')->user()) {
            $user = Auth::user();
            if ($user->id === 1) {
                $user = $this->dummyApiService->deleteUser($id);
                return ['success' => 'User deleted'];
            } else {
                return ['error' => 'Unauthorized'];
            }
        }
    }
}
