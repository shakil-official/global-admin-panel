<?php

namespace Modules\Client\Services;

use App\Engine\Base\Services\BaseService;
use Modules\Client\Services\Contracts\ClientServiceInterface;
use Modules\Client\Repositories\Contracts\ClientRepositoryInterface;


class ClientService extends BaseService implements ClientServiceInterface
{
    protected $repository;

    public function __construct(ClientRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to Client here
}
