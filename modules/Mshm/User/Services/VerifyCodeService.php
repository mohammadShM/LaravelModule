<?php

namespace Mshm\User\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class VerifyCodeService
{

    private static $min = 100000;
    private static $max = 999999;

    public static function generate()
    {
        try {
            return random_int(self::$min, self::$max);
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

    public static function get($id)
    {
        try {
            return Cache::get('verify_code_' . $id);
        } catch (Exception $e) {
            return abort(404, 'Not Cache Work , Please Try Again.');
        }
    }

    public static function delete($id)
    {
        try {
            return cache()->delete('verify_code_' . $id);
        } catch (InvalidArgumentException $e) {
            return abort(404, 'Not Cache Work , Please Try Again.');
        } catch (Exception $e) {
            return abort(404, 'Not Cache Work , Please Try Again.');
        }
    }

    public static function getRule()
    {
        return 'required|numeric|between:' . self::$min . ',' . self::$max;
    }

    public static function check($id, $code)
    {
        if (self::get($id) != $code) {
            return false;
        }
        self::delete($id);
        return true;
    }

}
