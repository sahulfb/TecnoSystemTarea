<?php

namespace App\Repositories;

use App\Models\Permission as ModelsPermission;
use Illuminate\Database\Eloquent\Model;

class TaskRepositories extends BaseRepository
{
    public function __construct(ModelsPermission $permiso)
    {
        parent::__construct($permiso);
    }

    public function all()
    {
        return $this->model->all();
    }

    public function findExist($key, $value)
    {
        return $this->model->where($key, $value)->exists();
    }

    public function validar($request)
    {
        $request->validate([
            'name' => ['required'],
            'display_name' => ['required'],
            'description' => ['required']
        ]);
    }

    public function create($request)
    {
        $model = $this->model->create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description
        ]);

        return $model;
    }

    public function updatePermiso($request, $id)
    {
        $model = $this->model->find($id);
        $model->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description
        ]);
    }

    public function deletePermiso($id)
    {
        $model = $this->model->find($id);
        $model->delete();
    }
}
