<?php

namespace Mshm\User\Database\Seeds;

use Illuminate\Database\Seeder;
use Mshm\User\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach (User::$defaultUsers as $user) {
            User::firstOrCreate(['email' => $user['email']], [
                'email' => $user['email'],
                'name' => $user['name'],
                'password' => bcrypt($user['password']),
            ])->assignRole($user['role'])->markEmailAsVerified();
        }

    }

}
