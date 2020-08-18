<?php

namespace Mshm\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Mshm\Media\Services\MediaFileService;

/**
 * @property mixed files
 * @property string type
 * @property int|string|null user_id
 * @property  mixed filename
 * @property mixed id
 * @method save()
 */
class Media extends Model
{

    protected $casts = [
        'files' => 'json'
    ];

    protected static function booted()
    {
        static::deleting(function ($media) {
            MediaFileService::delete($media);
        });
    }

    public function getThumbAttribute()
    {
        return '/storage/' . $this->files[300];
    }

}
