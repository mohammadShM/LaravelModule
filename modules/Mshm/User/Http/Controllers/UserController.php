<?php

namespace Mshm\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Mshm\RolePermissions\Repositories\RoleRepo;
use Mshm\User\Http\Requests\AddRoleRequest;
use Mshm\User\Models\User;
use Mshm\User\Repositories\UserRepo;
use function Mshm\Common\newFeedback;

class UserController extends Controller
{

    private $userRepo;

    public function __construct(UserRepo $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index(RoleRepo $roleRepo)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('index', User::class);
        $users = $this->userRepo->paginate();
        $roles = $roleRepo->all();
        return view('User::Admin.index', compact('users', 'roles'));
    }

    public function addRole(AddRoleRequest $request, User $user)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('addRole', User::class);
        $user->assignRole($request->role);
        newFeedback('موفقیت آمیز',
            "به کاربر {$request->role} نقش کاربری {$user->name} داده شد .", 'success');
        return back();
    }

}
