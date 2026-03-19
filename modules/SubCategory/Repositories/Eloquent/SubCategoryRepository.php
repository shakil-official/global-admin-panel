<?php

namespace Modules\SubCategory\Repositories\Eloquent;

use Modules\SubCategory\Models\SubCategory;
use Modules\SubCategory\Repositories\Contracts\SubCategoryRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;

class SubCategoryRepository extends BaseRepository implements SubCategoryRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(SubCategory::class); // Assuming your model is named SubCategory
    }

    // You can add custom methods specific to SubCategory here
}
