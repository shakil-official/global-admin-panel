<?php

namespace Modules\FaqCategory\Repositories\Eloquent;

use Modules\FaqCategory\Models\FaqCategory;
use Modules\FaqCategory\Repositories\Contracts\FaqCategoryRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;

class FaqCategoryRepository extends BaseRepository implements FaqCategoryRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(FaqCategory::class); // Assuming your model is named FaqCategory
    }

    // You can add custom methods specific to FaqCategory here
}
