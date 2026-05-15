<?php

namespace Modules\General\Services;

use App\Engine\Base\Services\BaseService;
use Modules\General\Services\Contracts\GeneralServiceInterface;
use Modules\General\Repositories\Contracts\GeneralRepositoryInterface;


class GeneralService extends BaseService implements GeneralServiceInterface
{
    protected $repository;

    public function __construct(GeneralRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to General here
}
