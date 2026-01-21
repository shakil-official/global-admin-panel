<?php

namespace App\Engine\SubCategory\Repositories\Eloquent;

use App\Engine\SubCategory\Repositories\Contracts\SubCategoryRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;
use App\Models\SubCategory;

class SubCategoryRepository extends BaseRepository implements SubCategoryRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(SubCategory::class); // Assuming your model is named SubCategory
    }

    // You can add custom methods specific to SubCategory here
}
