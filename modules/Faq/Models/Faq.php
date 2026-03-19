<?php

namespace Modules\Faq\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\File;
use Modules\FaqCategory\Models\FaqCategory;

class Faq extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id',
        'faq_category_id'
    ];


    /*
   |--------------------------------------------------------------------------
   | Relationships
   |--------------------------------------------------------------------------
   */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }


    protected static function booted(): void
    {
        static::deleting(function ($data) {
            if ($data->image && File::exists(public_path($data->image))) {
                File::delete(public_path($data->image));
            }
        });
    }
}
