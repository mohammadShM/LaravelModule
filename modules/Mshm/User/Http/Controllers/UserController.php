<?php

namespace Mshm\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Mshm\Common\Responses\AjaxResponses;
use Mshm\Media\Services\MediaFileService;
use Mshm\RolePermissions\Repositories\RoleRepo;
use Mshm\User\Http\Requests\AddRoleRequest;
use Mshm\User\Http\Requests\UpdateProfileInformationRequest;
use Mshm\User\Http\Requests\UpdateUserPhotoRequest;
use Mshm\User\Http\Requests\UpdateUserRequest;
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

    public function edit($userId, RoleRepo $roleRepo)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('edit', User::class);
        $user = $this->userRepo->findById($userId);
        $roles = $roleRepo->all();
        return view('User::Admin.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, $userId)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('edit', User::class);
        $user = $this->userRepo->findById($userId);
        if ($request->hasFile('image')) {
            $request->request->add(['image_id' => MediaFileService::upload($request->file('image'))->id]);
            if ($user->image) {
                $user->image->delete();
            }
        } else {
            $request->request->add(['image_id' => $user->image_id]);
        }
        $this->userRepo->update($userId, $request);
        newFeedback();
        return redirect()->back();
    }

    public function updatePhoto(UpdateUserPhotoRequest $request)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('editProfile', User::class);
        $media = MediaFileService::upload($request->file('userPhoto'));
        if (auth()->user()->image) {
            auth()->user()->image->delete();
        }
        auth()->user()->image_id = $media->id;
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->save();
        newFeedback();
        return back();
    }

    public function profile()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('editProfile', User::class);
        return view('User::admin.profile');
    }

    public function updateProfile(UpdateProfileInformationRequest $request)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('editProfile', User::class);
        $this->userRepo->updateProfile($request);
        newFeedback();
        return back();
    }

    public function destroy($userId)
    {
        $user = $this->userRepo->findById($userId);
        /** @noinspection PhpUnhandledExceptionInspection */
        $user->delete();
        return AjaxResponses::successResponse();
    }

    public function manualVerify($userId)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('manualVerify', User::class);
        $user = $this->userRepo->findById($userId);
        $user->markEmailAsVerified();
        return AjaxResponses::successResponse();
    }

    public function addRole(AddRoleRequest $request, User $user)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('addRole', User::class);
        $user->assignRole($request->role);
        newFeedback('موفقیت آمیز',
            " به کاربر {$request->role} نقش کاربری {$user->name} داده شد . ", 'success');
        return back();
    }

    public function removeRole($userId, $role)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('removeRole', User::class);
        $user = $this->userRepo->findById($userId);
        $user->removeRole($role);
        return AjaxResponses::successResponse();
    }

}
