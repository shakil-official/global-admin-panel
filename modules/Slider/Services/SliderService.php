<?php

namespace Modules\Slider\Services;

use App\Engine\Base\Services\BaseService;
use Modules\Slider\Services\Contracts\SliderServiceInterface;
use Modules\Slider\Repositories\Contracts\SliderRepositoryInterface;


class SliderService extends BaseService implements SliderServiceInterface
{
    protected $repository;

    public function __construct(SliderRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to Slider here
}
