<?php

namespace App\Engine\About\Services;

use App\Engine\Base\Services\BaseService;
use App\Engine\About\Services\Contracts\AboutServiceInterface;
use App\Engine\About\Repositories\Contracts\AboutRepositoryInterface;



class AboutService extends BaseService implements AboutServiceInterface
{
    protected $repository;

    public function __construct(AboutRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to About here
}
