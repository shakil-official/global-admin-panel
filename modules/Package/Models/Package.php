<?php

namespace Modules\Package\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Package extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'image',
        'user_id',
        'category',
        'speed',
        'base_price',
        'vat_enabled',
        'vat_percentage',
        'vat_amount',
        'total_price',
        'is_popular',
        'features',
        'short_description',
        'description',
        'type',
        'status',
    ];

    protected $casts = [
        'features' => 'array',
        'vat_enabled' => 'boolean',
        'is_popular' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($data) {
            $data->slug = Str::slug($data->title);
        });

        static::updating(function ($data) {
            $data->slug = Str::slug($data->title);
        });

        static::deleting(function ($data) {
            if ($data->image && File::exists(public_path($data->image))) {
                File::delete(public_path($data->image));
            }
        });
    }
}
