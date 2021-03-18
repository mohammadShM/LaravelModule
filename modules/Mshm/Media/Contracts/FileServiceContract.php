<?php

namespace Mshm\Media\Contracts;

use Illuminate\Http\UploadedFile;
use Mshm\Media\Models\Media;

interface FileServiceContract
{
    public static function upload(UploadedFile $file, string $filename, string $dir): array;

    public static function delete(Media  $media);

    public static function thumb(Media  $media);

    public static function stream(Media  $media);
}
