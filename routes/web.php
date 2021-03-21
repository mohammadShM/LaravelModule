<?php

//Route::get('/', function () {
//    return view('index');
//});

// for set handle role permissions in user login
//use Mshm\Payment\Gateways\Gateway;
//use Mshm\Payment\Models\Payment;

//Route::get('/test', function () {
//    // for create permission by package laravel permission
////    Permission::create([
////         'name' => 'manage categories'
////    ]);
//    // for give permission by package laravel permission
////    auth()->user()->givePermissionTo('manage categories');
//    // for show permission by package laravel permission
////    return auth()->user()->permissions;
//    // for all 3 level
//    // Permission::create(['name'=>'manage role_permissions']);
////        auth()->user()->givePermissionTo(Permission::PERMISSION_SUPER_ADMIN);
////        return auth()->user()->permissions;
//    // for set role
////    $gateway = resolve(Gateway::class);
////    $payment = new Payment();
////    $gateway->request($payment);
//    // return auth()->user()->assignRole('teacher');
//});
