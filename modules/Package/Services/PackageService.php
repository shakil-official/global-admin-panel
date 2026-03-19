<?php

namespace Modules\Package\Services;

use App\Engine\Base\Services\BaseService;
use Modules\Package\Services\Contracts\PackageServiceInterface;
use Modules\Package\Repositories\Contracts\PackageRepositoryInterface;


class PackageService extends BaseService implements PackageServiceInterface
{
    protected $repository;

    public function __construct(PackageRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to Package here
}
