<?php

namespace Modules\General\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class General extends Model
{
    protected $fillable = [
        'title',
        'whats_app_contact',
        'facebook_contact',
        'fav',
        'icon',
        'btrc_document_file',
        'social_facebook_link',
        'social_youtube_link',
        'social_linkdin_link',
        'social_instragram_link',
        'status',
        'user_id',
    ];

    protected static function booted(): void
    {
        static::saved(function () {

            Cache::forget('general_single_data');
        });

        static::deleted(function () {

            Cache::forget('general_single_data');
        });
    }
}
