<?php

use Spatie\Permission\Models\Permission;

Route::get('/', function () {
    return view('index');
});

Route::get('/test', function () {
    // for create permission by package laravel permission
//    Permission::create([
//         'name' => 'manage categories'
//    ]);
    // for give permission by package laravel permission
//    auth()->user()->givePermissionTo('manage categories');
    // for show permission by package laravel permission
//    return auth()->user()->permissions;
    // for all 3 level
//    Permission::create(['name'=>'manage role_permissions']);
//        auth()->user()->givePermissionTo('manage role_permissions');
//        return auth()->user()->permissions;
});
