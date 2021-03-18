<?php

namespace Mshm\Media\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mshm\Media\Models\Media;
use Mshm\Media\Services\MediaFileService;

class MediaController extends Controller
{

    public function download(Media $media, Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }
        return MediaFileService::stream($media);
    }

}
