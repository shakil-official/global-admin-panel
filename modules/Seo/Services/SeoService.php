<?php

namespace Modules\Seo\Services;

use App\Engine\Base\Services\BaseService;
use Modules\Seo\Services\Contracts\SeoServiceInterface;
use Modules\Seo\Repositories\Contracts\SeoRepositoryInterface;


class SeoService extends BaseService implements SeoServiceInterface
{
    protected $repository;

    public function __construct(SeoRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to Seo here
}
