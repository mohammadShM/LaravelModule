<?php

namespace Mshm\Media\Services;

use Illuminate\Support\Facades\Storage;
use Mshm\Media\Contracts\FileServiceContract;

class ZipFileService extends DefaultFileService implements FileServiceContract
{

    public static function upload($file, $filename, $dir): array
    {
        Storage::putFileAs($dir, $file, $filename . '.' . $file->getClientOriginalExtension());
        return ["zip" => $filename . '.' . $file->getClientOriginalExtension()];
    }

}
