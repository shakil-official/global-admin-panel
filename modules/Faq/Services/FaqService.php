<?php

namespace Modules\Faq\Services;

use App\Engine\Base\Services\BaseService;
use Modules\Faq\Services\Contracts\FaqServiceInterface;
use Modules\Faq\Repositories\Contracts\FaqRepositoryInterface;


class FaqService extends BaseService implements FaqServiceInterface
{
    protected $repository;

    public function __construct(FaqRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to Faq here
}
