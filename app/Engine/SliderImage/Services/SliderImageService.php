<?php

namespace App\Engine\SliderImage\Services;

use App\Engine\Base\Services\BaseService;
use App\Engine\SliderImage\Services\Contracts\SliderImageServiceInterface;
use App\Engine\SliderImage\Repositories\Contracts\SliderImageRepositoryInterface;



class SliderImageService extends BaseService implements SliderImageServiceInterface
{
    protected $repository;

    public function __construct(SliderImageRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to SliderImage here
}
