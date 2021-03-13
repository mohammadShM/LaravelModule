<?php

namespace Mshm\Media\Services;

use Illuminate\Support\Facades\Storage;
use Mshm\Media\Contracts\FileServiceContract;
use Mshm\Media\Models\Media;

class VideoFileService extends DefaultFileService implements FileServiceContract
{

    public static function upload($file, $filename, $dir): array
    {
        Storage::putFileAs($dir, $file, $filename . '.' . $file->getClientOriginalExtension());
        return ["video" => $filename . '.' . $file->getClientOriginalExtension()];
    }

    public static function thumb(Media $media)
    {
        return url('/img/video-thumb.png');
    }
}
