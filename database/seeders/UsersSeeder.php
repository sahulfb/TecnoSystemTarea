<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Laratrust\Laratrust;
use Laratrust\Models\Role as ModelsRole;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuarios de prueba
        $admin = User::factory()->create([
            'name' => 'Admin admin',
            'email' => 'admin2@example.com',
            'password' => bcrypt('password'),
        ]);

        $employee = User::factory()->create([
            'name' => 'eployee',
            'email' => 'employee@example.com',
            'password' => bcrypt('password'),
        ]);

        $user = User::factory()->create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);

        // Obtener el rol "admin" desde la base de datos
        $adminRole = Role::where('name', 'admin')->first();
        $employeeRole = Role::where('name', 'employee')->first();
        $userRole = Role::where('name', 'user')->first();
        //  $editUserPermission = Permission::where('name', 'users-create')->first();
        // Obtener todos los permisos
        // Asignar el rol "admin" al usuario
        $admin->addRole($adminRole);
        $employee->addRole($employeeRole);
        $user->addRole($userRole);
        $adminPermissions = ['users-create', 'users-read', 'users-update', 'users-delete'];
        $rolPermissions = ['users-read', 'users-update', 'users-delete'];
        $userPermissions = ['users-read'];
        $this->createPermission($adminRole, $adminPermissions);
    }

    public function createPermission($rol, $allPermissions)
    {
        foreach ($allPermissions as $permission) {
            $editUserPermission = Permission::where('name', $permission)->first();
            $rol->givePermission($editUserPermission);
        }
    }
}
