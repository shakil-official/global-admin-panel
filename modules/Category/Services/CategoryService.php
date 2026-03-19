<?php

namespace Modules\Category\Services;

use App\Engine\Base\Services\BaseService;
use Modules\Category\Services\Contracts\CategoryServiceInterface;
use Modules\Category\Repositories\Contracts\CategoryRepositoryInterface;


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
