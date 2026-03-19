<?php

namespace Modules\NetworkPartner\Models;

use Illuminate\Database\Eloquent\Model;

class NetworkPartner extends Model
{
    protected $fillable = [
        'name',
        'image',
        'type',
        'user_id',
        'status',
    ];

}
