<?php

namespace Modules\Offer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Offer extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'image',
        'description',
        'user_id',
        'status',
    ];

    protected static function booted(): void
    {
        static::creating(function ($offer) {
            if (empty($offer->slug)) {
                $offer->slug = static::generateUniqueSlug($offer->title);
            }
        });

        static::updating(function ($offer) {
            if ($offer->isDirty('title')) {
                $offer->slug = static::generateUniqueSlug(
                    $offer->title,
                    $offer->id
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
