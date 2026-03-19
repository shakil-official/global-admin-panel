<?php

namespace Modules\Category\Repositories\Eloquent;

use Modules\Category\Models\Category;
use Modules\Category\Repositories\Contracts\CategoryRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Category::class); // Assuming your model is named Category
    }

    // You can add custom methods specific to Category here
}
