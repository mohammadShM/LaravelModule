<?php

use Mshm\Media\Services\ImageFileService;
use Mshm\Media\Services\VideoFileService;
use Mshm\Media\Services\ZipFileService;

return [
    "mediaTypeServices" => [
        "image" => [
            "extensions" => [
                "png", "jpg", "jpeg",
            ],
            "handler" => ImageFileService::class,
        ],
        "video" => [
            "extensions" => [
                "avi", "mp4", "m4v", "mkv",
            ],
            "handler" => VideoFileService::class,
        ],
        "zip" => [
            "extensions" => [
                "zip", "rar", "tar",
            ],
            "handler" => ZipFileService::class,
        ],
    ]
];
