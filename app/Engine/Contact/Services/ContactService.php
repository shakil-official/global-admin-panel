<?php

namespace App\Engine\Contact\Services;

use App\Engine\Base\Services\BaseService;
use App\Engine\Contact\Services\Contracts\ContactServiceInterface;
use App\Engine\Contact\Repositories\Contracts\ContactRepositoryInterface;



class ContactService extends BaseService implements ContactServiceInterface
{
    protected $repository;

    public function __construct(ContactRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to Contact here
}
