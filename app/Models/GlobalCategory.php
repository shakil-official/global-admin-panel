<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GlobalCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'status',
    ];

    public function subCategories(): HasMany
    {
        return $this->hasMany(GlobalSubCategory::class, 'global_category_id');
    }


}
