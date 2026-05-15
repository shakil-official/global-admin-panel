<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormStore extends Model
{
    use HasFactory;

    const SOURCE_PACKAGE = 'PACKAGE_WEB_REQUEST';

    protected $fillable = [
        'name',
        'mobile',
        'address',
        'type',
        'extra_data',
        'message',
        'package',
        'status',
        'source',
    ];
}
