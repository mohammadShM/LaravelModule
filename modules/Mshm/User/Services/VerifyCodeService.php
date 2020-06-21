<?php

namespace Mshm\User\Services;

use Exception;
use Psr\SimpleCache\InvalidArgumentException;

class VerifyCodeService
{

    public static function generate()
    {
        try {
            return random_int(100000, 999999);
        } catch (Exception $e) {
            return abort(404, 'Not Send Code , Please Try Again.');
        }
    }

    public static function store($id, $code)
    {
        try {
            return cache()->set('verify_code_' . $id, $code, now()->addDay());
        } catch (InvalidArgumentException $e) {
            return abort(404, 'Not Cache Work , Please Try Again.');
        } catch (Exception $e) {
            return abort(404, 'Not Cache Work , Please Try Again.');
        }
    }

}
