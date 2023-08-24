<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DummyApiServices
{

    public function getApiExternal(string $url)
    {
        $response = Http::withoutVerifying()
            ->baseUrl(env('DUMMY_BASE_URL'))
            ->withHeaders(['app-id' => env('DUMMY_APP_ID')])
            ->get($url);

        return $response->json();
    }
    public function getUsers()
    {
        try {
            $data = $this->getApiExternal('user?limit=10');

            return $data;
        } catch (HttpException $e) {
            return ['error' => 'Error al obtener usuarios: ' . $e->getStatusCode()];
        }
    }

    public function getUserId($id)
    {
        try {
            $data = $this->validation('user/', $id);

            return $data;
        } catch (HttpException $e) {
            return ['error' => 'Error al obtener usuarios: ' . $e->getStatusCode()];
        }
    }

    public function validation($url, $dataId)
    {
        try {
            $data = $this->getApiExternal($url . '' . $dataId);
            return $data;
        } catch (HttpException $e) {
            return ['error' => 'Error al obtener usuarios: ' . $e->getStatusCode()];
        }
    }

    public function updateUser($id, $dato, $valor)
    {

        $response = Http::withoutVerifying()
            ->baseUrl(env('DUMMY_BASE_URL'))
            ->withHeaders([
                'app-id' => env('DUMMY_APP_ID'),
                'Content-Type' => 'application/json',
            ])
            ->put('user/' . $id, [
                $dato => $valor,
            ]);

        // Obtén la respuesta como arreglo JSON
        $responseData = $response->json();
        return $responseData;
    }

    public function deleteUser($id)
    {
        $response = Http::withoutVerifying()
            ->baseUrl(env('DUMMY_BASE_URL'))
            ->withHeaders([
                'app-id' => env('DUMMY_APP_ID'),
                'Content-Type' => 'application/json',
            ])
            ->delete('user/' . $id);
        // Obtén la respuesta como arreglo JSON
        $responseData = $response->json();
        return $responseData;
    }
}
