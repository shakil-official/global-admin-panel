<?php

namespace Modules\FaqCategory\Services;

use App\Engine\Base\Services\BaseService;
use Modules\FaqCategory\Services\Contracts\FaqCategoryServiceInterface;
use Modules\FaqCategory\Repositories\Contracts\FaqCategoryRepositoryInterface;


class FaqCategoryService extends BaseService implements FaqCategoryServiceInterface
{
    protected $repository;

    public function __construct(FaqCategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to FaqCategory here
}
