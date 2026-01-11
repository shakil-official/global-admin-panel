<?php

namespace App\Engine\GlobalSubCategory\Services;

use App\Engine\Base\Services\BaseService;
use App\Engine\GlobalSubCategory\Services\Contracts\GlobalSubCategoryServiceInterface;
use App\Engine\GlobalSubCategory\Repositories\Contracts\GlobalSubCategoryRepositoryInterface;



class GlobalSubCategoryService extends BaseService implements GlobalSubCategoryServiceInterface
{
    protected $repository;

    public function __construct(GlobalSubCategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to GlobalSubCategory here
}
