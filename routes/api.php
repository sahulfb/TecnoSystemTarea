<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\BanksController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('banks', BanksController::class);

    Route::post('getDatosToken', [AuthController::class, 'getDatosToken']);
    Route::post('validarToken', [AuthController::class, 'validarToken']);
    Route::post('logout', [AuthController::class, 'logout']);
    // Roles
    Route::get('roles', [RolesController::class, 'index'])->name('roles.index');
    Route::post('roles', [RolesController::class, 'store'])->name('roles.store');
    Route::put('roles/{rol}', [RolesController::class, 'update'])->name('roles.update');
    Route::delete('roles/{rol}', [RolesController::class, 'destroy'])->name('roles.destroy');
    //  Route::post('roles/asignar', [RolesController::class, 'asignar']);

    // Permissions
    Route::post('permissions', [PermissionsController::class, 'index']);
    Route::post('permissions/create', [PermissionsController::class, 'store']);
    Route::post('permissions/update/{id}', [PermissionsController::class, 'update']);
    Route::post('permissions/delete/{id}', [PermissionsController::class, 'destroy']);
    Route::post('permissions/asignar/{permiso}/user/{user}', [PermissionsController::class, 'asignar']);

    //Dummy Api
    Route::post('services', [ServiceController::class, 'index']);
    Route::post('services/create', [ServiceController::class, 'store']);
    Route::post('services/search/{id}', [ServiceController::class, 'show']);
    Route::post('services/update/{id}', [ServiceController::class, 'update']);
    Route::post('services/delete/{id}', [ServiceController::class, 'destroy']);
});
Route::post('login', [AuthController::class, 'login']);
Route::post('webhook', WebhookController::class);
