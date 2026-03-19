<?php

namespace Modules\Offer\Services;

use App\Engine\Base\Services\BaseService;
use Modules\Offer\Services\Contracts\OfferServiceInterface;
use Modules\Offer\Repositories\Contracts\OfferRepositoryInterface;


class OfferService extends BaseService implements OfferServiceInterface
{
    protected $repository;

    public function __construct(OfferRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to Offer here
}
