<?php

namespace Mshm\Media\Services;

use Mshm\Media\Models\Media;

class MediaFileService
{

    public static function upload($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        switch ($extension) {
            case 'jpg':
            case 'png':
            case 'jpeg':
                $media = new Media();
                $media->files = ImageFileService::upload($file);
                $media->type = 'image';
                $media->user_id = auth()->id();
                $media->filename = $file->getClientOriginalExtension();
                $media->save();
                return $media;
//            case 'zip':
//            case 'rar':
//            case 'tar':
            case 'avi':
            case 'mp4':
                VideoFileService::upload($file);
                break;
            default:
                return null;
        }
        return null;
    }

    public static function delete($media)
    {
        switch ($media->type) {
            case 'image':
                ImageFileService::delete($media);
                break;
        }
    }

}
