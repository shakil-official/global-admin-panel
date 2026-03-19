<?php

namespace Modules\Setting\Services;

use App\Engine\Base\Services\BaseService;
use Modules\Setting\Services\Contracts\SettingServiceInterface;
use Modules\Setting\Repositories\Contracts\SettingRepositoryInterface;


class SettingService extends BaseService implements SettingServiceInterface
{
    protected $repository;

    public function __construct(SettingRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to Setting here
}
