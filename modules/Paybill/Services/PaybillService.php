<?php

namespace Modules\Paybill\Services;

use App\Engine\Base\Services\BaseService;
use Modules\Paybill\Services\Contracts\PaybillServiceInterface;
use Modules\Paybill\Repositories\Contracts\PaybillRepositoryInterface;


class PaybillService extends BaseService implements PaybillServiceInterface
{
    protected $repository;

    public function __construct(PaybillRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to Paybill here
}
