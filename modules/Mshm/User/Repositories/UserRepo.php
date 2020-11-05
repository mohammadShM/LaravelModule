<?php

namespace Mshm\User\Repositories;

use Mshm\RolePermissions\Models\Permission;
use Mshm\User\Models\User;

class UserRepo
{

    public function findByEmail($email)
    {
        return User::query()->where('email', $email)->first();
    }

    public function getTeachers()
    {
        return User::permission(Permission::PERMISSION_TEACH)->get();
    }

    public function findById($id)
    {
        return User::findOrFail($id);
    }

    public function paginate()
    {
        return User::paginate();
    }

    public function update($userId, $values)
    {
        $update = [
            'name' => $values->name,
            'email' => $values->email,
            'mobile' => $values->mobile,
            'username' => $values->username,
            'headline' => $values->headline,
            'website' => $values->website,
            'instagram' => $values->instagram,
            'twitter' => $values->twitter,
            'facebook' => $values->facebook,
            'youtube' => $values->youtube,
            'status' => $values->status,
            'image_id' => $values->image_id,
        ];
        if (!is_null($values->password)) {
            $update['password'] = bcrypt($values->password);
        }
        // 5 lines for set role for user and delete old role
        $user = User::find($userId);
        $user->syncRoles([]);
        if ($values['role']) {
            $user->assignRole($values['role']);
        }
        return User::where('id', $userId)->update($update);
    }

    /** @noinspection PhpPossiblePolymorphicInvocationInspection */
    public function updateProfile($request)
    {
        auth()->user()->name = $request->name;
        if (auth()->user()->email != $request->email) {
            auth()->user()->email = $request->email;
            auth()->user()->email_verified_at = null;
        }
        /** @noinspection PhpUndefinedMethodInspection */
        if (auth()->user()->hasPermissionTo(Permission::PERMISSION_TEACH)) {
            auth()->user()->card_number = $request->card_number;
            auth()->user()->shaba = $request->shaba;
            auth()->user()->headline = $request->headline;
            auth()->user()->bio = $request->bio;
            auth()->user()->username = $request->username;
        }
        if ($request->password) {
            auth()->user()->password = bcrypt($request->password);
        }
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->save();
    }

}
