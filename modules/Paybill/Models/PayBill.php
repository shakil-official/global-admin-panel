<?php

namespace Modules\Paybill\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PayBill extends Model
{
    protected $fillable = [
        'method',
        'title',
        'description',
        'step_no',
        'meta',
        'user_id',
        'status'
    ];

    protected $casts = [
        'meta' => 'array'
    ];

    protected static function booted(): void
    {
        static::deleting(function ($data) {
            if ($data->image && File::exists(public_path($data->image))) {
                File::delete(public_path($data->image));
            }
        });

    }


}
