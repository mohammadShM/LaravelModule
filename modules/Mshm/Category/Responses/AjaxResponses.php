<?php

namespace Mshm\Category\Responses;

use Illuminate\Http\Response;

class AjaxResponses
{

    public static function successResponse()
    {
        return response()->json(['message' => 'عملیات با موفقیت انجام شد .'], Response::HTTP_OK);

    }

}
