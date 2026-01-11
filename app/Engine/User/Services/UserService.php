<?php

namespace App\Engine\User\Services;

use App\Engine\Base\Services\BaseService;
use App\Engine\User\Services\Contracts\UserServiceInterface;
use App\Engine\User\Repositories\Contracts\UserRepositoryInterface;



class UserService extends BaseService implements UserServiceInterface
{
    protected $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to About here
}
