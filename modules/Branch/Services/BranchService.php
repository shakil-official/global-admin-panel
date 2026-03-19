<?php

namespace Modules\Branch\Services;

use App\Engine\Base\Services\BaseService;
use Modules\Branch\Services\Contracts\BranchServiceInterface;
use Modules\Branch\Repositories\Contracts\BranchRepositoryInterface;


class BranchService extends BaseService implements BranchServiceInterface
{
    protected $repository;

    public function __construct(BranchRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to Branch here
}
