<?php

namespace App\Engine\Category\Services;

use App\Engine\Base\Services\BaseService;
use App\Engine\Category\Services\Contracts\CategoryServiceInterface;
use App\Engine\Category\Repositories\Contracts\CategoryRepositoryInterface;



class CategoryService extends BaseService implements CategoryServiceInterface
{
    protected $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to Category here
}
