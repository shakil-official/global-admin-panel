<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GlobalSubCategory extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'slug',
        'position',
        'reason',
        'global_category_id',
        'description',
        'image',
        'icon',
        'status',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(GlobalCategory::class, 'global_category_id');
    }


}
