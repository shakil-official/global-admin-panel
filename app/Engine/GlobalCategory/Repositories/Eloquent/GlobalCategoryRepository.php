<?php

namespace App\Engine\GlobalCategory\Repositories\Eloquent;

use App\Engine\GlobalCategory\Repositories\Contracts\GlobalCategoryRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;
use App\Models\GlobalCategory;

class GlobalCategoryRepository extends BaseRepository implements GlobalCategoryRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(GlobalCategory::class); // Assuming your model is named GlobalCategory
    }

    // You can add custom methods specific to GlobalCategory here
}
