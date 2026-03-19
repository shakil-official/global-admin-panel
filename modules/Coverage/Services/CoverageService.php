<?php

namespace Modules\Coverage\Services;

use App\Engine\Base\Services\BaseService;
use Modules\Coverage\Services\Contracts\CoverageServiceInterface;
use Modules\Coverage\Repositories\Contracts\CoverageRepositoryInterface;


class CoverageService extends BaseService implements CoverageServiceInterface
{
    protected $repository;

    public function __construct(CoverageRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to Coverage here
}
