<?php

namespace Modules\SubCategory\Services;

use App\Engine\Base\Services\BaseService;
use Modules\SubCategory\Services\Contracts\SubCategoryServiceInterface;
use Modules\SubCategory\Repositories\Contracts\SubCategoryRepositoryInterface;


class SubCategoryService extends BaseService implements SubCategoryServiceInterface
{
    protected $repository;

    public function __construct(SubCategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to SubCategory here
}
