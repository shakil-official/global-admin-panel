<?php

namespace Modules\Tag\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'status',
    ];
}
