<?php

namespace Modules\Branch\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Branch extends Model
{
     protected $table = "branches";

     protected $fillable = [
        'title',
        'slug',
        'image',
        'short_description',
        'description',
        'type',
        'status',
    ];

    protected static function booted(): void
    {
        static::creating(function ($data) {
            if (empty($data->slug)) {
                $data->slug = static::generateUniqueSlug($data->title);
            }
        });

        static::updating(function ($data) {
            if ($data->isDirty('title')) {
                $data->slug = static::generateUniqueSlug(
                    $data->title,
                    $data->id
                );
            }
        });

        static::deleting(function ($data) {
            if ($data->image && File::exists(public_path($data->image))) {
                File::delete(public_path($data->image));
            }
        });

    }

    protected static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (
        static::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
