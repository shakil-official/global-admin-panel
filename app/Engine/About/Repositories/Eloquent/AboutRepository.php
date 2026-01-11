<?php

namespace App\Engine\About\Repositories\Eloquent;

use App\Engine\About\Repositories\Contracts\AboutRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;
use App\Models\About;

class AboutRepository extends BaseRepository implements AboutRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(About::class); // Assuming your model is named About
    }

    // You can add custom methods specific to About here
}
