<?php

namespace App\Engine\GlobalCategory\Services;

use App\Engine\Base\Services\BaseService;
use App\Engine\GlobalCategory\Services\Contracts\GlobalCategoryServiceInterface;
use App\Engine\GlobalCategory\Repositories\Contracts\GlobalCategoryRepositoryInterface;



class GlobalCategoryService extends BaseService implements GlobalCategoryServiceInterface
{
    protected $repository;

    public function __construct(GlobalCategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to GlobalCategory here
}
