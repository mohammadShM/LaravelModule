<?php

namespace Mshm\RolePermissions\Http\Controllers;

use App\Http\Controllers\Controller;
use Mshm\Category\Responses\AjaxResponses;
use Mshm\RolePermissions\Http\Requests\RoleRequest;
use Mshm\RolePermissions\Http\Requests\RoleUpdateRequest;
use Mshm\RolePermissions\Models\Role;
use Mshm\RolePermissions\Repositories\PermissionRepo;
use Mshm\RolePermissions\Repositories\RoleRepo;

class RolePermissionsController extends Controller
{

    private $roleRepo;
    private $permissionRepo;

    public function __construct(RoleRepo $roleRepo, PermissionRepo $permissionRepo)
    {
        $this->roleRepo = $roleRepo;
        $this->permissionRepo = $permissionRepo;
    }

    public function index()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('index', Role::class);
        $roles = $this->roleRepo->all();
        $permissions = $this->permissionRepo->all();
        return view('RolePermissions::index', compact('roles', 'permissions'));
    }

    public function store(RoleRequest $request)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('create', Role::class);
        $this->roleRepo->create($request);
        return redirect(route('role-permissions.index'));
    }

    public function edit($roleId)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('edit', Role::class);
        $role = $this->roleRepo->findById($roleId);
        $permissions = $this->permissionRepo->all();
        return view("RolePermissions::edit", compact("role", "permissions"));
    }

    public function update(RoleUpdateRequest $request, $id)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('edit', Role::class);
        $this->roleRepo->update($id, $request);
        return redirect(route('role-permissions.index'));
    }

    public function destroy($roleId)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('delete', Role::class);
        $this->roleRepo->delete($roleId);
        return AjaxResponses::successResponse();
    }

}
