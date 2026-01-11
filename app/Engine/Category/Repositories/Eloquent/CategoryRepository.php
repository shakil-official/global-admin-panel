<?php

namespace App\Engine\Category\Repositories\Eloquent;

use App\Engine\Category\Repositories\Contracts\CategoryRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;
use App\Models\Category;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Category::class); // Assuming your model is named Category
    }

    // You can add custom methods specific to Category here
}
