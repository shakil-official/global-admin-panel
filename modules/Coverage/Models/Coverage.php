<?php

namespace Modules\Coverage\Models;

use Illuminate\Database\Eloquent\Model;

class Coverage extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'status'
    ];

}
