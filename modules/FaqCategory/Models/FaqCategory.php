<?php

namespace Modules\FaqCategory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FaqCategory extends Model
{
    protected $fillable = [
        'title',
        'user_id',
        'status',
    ];

    protected static function booted(): void
    {
        static::deleting(function ($data) {
            if ($data->image && File::exists(public_path($data->image))) {
                File::delete(public_path($data->image));
            }
        });

    }
}
