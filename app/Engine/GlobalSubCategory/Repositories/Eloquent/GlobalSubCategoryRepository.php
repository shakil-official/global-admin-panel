<?php

namespace App\Engine\GlobalSubCategory\Repositories\Eloquent;

use App\Engine\GlobalSubCategory\Repositories\Contracts\GlobalSubCategoryRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;
use App\Models\GlobalSubCategory;

class GlobalSubCategoryRepository extends BaseRepository implements GlobalSubCategoryRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(GlobalSubCategory::class); // Assuming your model is named GlobalSubCategory
    }

    // You can add custom methods specific to GlobalSubCategory here
}
