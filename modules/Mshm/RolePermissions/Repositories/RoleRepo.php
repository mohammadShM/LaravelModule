<?php

namespace Mshm\RolePermissions\Repositories;

use Spatie\Permission\Models\Role;

class RoleRepo
{

    public function all()
    {
        return Role::all();
    }

    public function findById($id)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return Role::findOrFail($id);
    }

    public function create($request)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return Role::create(['name' => $request->name])->syncPermissions($request->permissions);
    }

    public function update($id, $request)
    {
        $role = $this->findById($id);
        return $role->syncPermissions($request->permissions)->update(['name' => $request->name]);
    }

    public function delete($id)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return Role::where('id',$id)->delete();
    }

}
