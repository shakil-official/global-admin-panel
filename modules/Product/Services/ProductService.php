<?php

namespace Modules\Product\Services;

use App\Engine\Base\Services\BaseService;
use Modules\Product\Services\Contracts\ProductServiceInterface;
use Modules\Product\Repositories\Contracts\ProductRepositoryInterface;


class ProductService extends BaseService implements ProductServiceInterface
{
    protected $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to Product here
}
