<?php

namespace Modules\SubCategory\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'name',
        'category_id',
        'user_id',
        'position',
        'image',
        'status',
    ];
}
