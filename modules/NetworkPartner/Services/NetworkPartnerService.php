<?php

namespace Modules\NetworkPartner\Services;

use App\Engine\Base\Services\BaseService;
use Modules\NetworkPartner\Services\Contracts\NetworkPartnerServiceInterface;
use Modules\NetworkPartner\Repositories\Contracts\NetworkPartnerRepositoryInterface;


class NetworkPartnerService extends BaseService implements NetworkPartnerServiceInterface
{
    protected $repository;

    public function __construct(NetworkPartnerRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to NetworkPartner here
}
