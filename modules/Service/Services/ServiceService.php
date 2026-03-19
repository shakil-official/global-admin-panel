<?php

namespace Modules\Service\Services;

use App\Engine\Base\Services\BaseService;
use Modules\Service\Services\Contracts\ServiceServiceInterface;
use Modules\Service\Repositories\Contracts\ServiceRepositoryInterface;


class ServiceService extends BaseService implements ServiceServiceInterface
{
    protected $repository;

    public function __construct(ServiceRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to Service here
}
