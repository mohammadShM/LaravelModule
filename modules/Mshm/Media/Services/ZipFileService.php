<?php
/** @noinspection ReturnTypeCanBeDeclaredInspection */

/** @noinspection PhpMissingReturnTypeInspection */

namespace Mshm\Media\Services;

use Illuminate\Support\Facades\Storage;
use Mshm\Media\Contracts\FileServiceContract;
use Mshm\Media\Models\Media;

class ZipFileService extends DefaultFileService implements FileServiceContract
{

    public static function upload($file, $filename, $dir): array
    {
        Storage::putFileAs($dir, $file, $filename . '.' . $file->getClientOriginalExtension());
        return ["zip" => $filename . '.' . $file->getClientOriginalExtension()];
    }

    public static function thumb(Media $media)
    {
        return url('/img/zip-thumb.png');
    }

    public static function getFileName()
    {
        return (static::$media->is_private ? 'private/' : 'public/') . static::$media->files['zip'];
    }

}
