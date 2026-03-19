<?php

namespace Modules\Service\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'title',
        'position',
        'icon',
        'service',
        'description',
        'section',
        'button_text',
        'button_url',
        'user_id',
    ];
}


