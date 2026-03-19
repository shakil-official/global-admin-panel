<?php

namespace Modules\Feedback\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = "feedbacks";

    protected $fillable = [
        'title',
        'name',
        'rating',
        'user_id',
        'message',
    ];


}
