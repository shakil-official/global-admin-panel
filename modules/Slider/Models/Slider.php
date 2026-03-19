<?php

namespace Modules\Slider\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Slider extends Model
{
    protected $fillable = [
        // Basic info
        'name',
        'image',
        'position',
        'status',
        'type',

        // Content
        'badge',
        'headline',
        'description',

        // Button info
        'button_text',
        'button_url',
        'button_icon',
        'button_classes',

        // Overlay & extra
        'overlay_enabled',
        'feature_pills',

        // User
        'user_id',
    ];

    // Cast feature_pills as array automatically
    protected $casts = [
        'feature_pills' => 'array',
        'overlay_enabled' => 'boolean',
    ];

    // Optional: define user relationship

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
