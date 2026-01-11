<?php

namespace App\Engine\User\Repositories\Eloquent;

use App\Engine\About\Repositories\Contracts\AboutRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;
use App\Models\User;

class UserRepository extends BaseRepository implements AboutRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(User::class); // Assuming your model is named About
    }

    // You can add custom methods specific to About here
}
