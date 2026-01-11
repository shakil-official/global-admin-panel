<?php

namespace App\Engine\TermsAndCondition\Services;

use App\Engine\Base\Services\BaseService;
use App\Engine\TermsAndCondition\Services\Contracts\TermsAndConditionServiceInterface;
use App\Engine\TermsAndCondition\Repositories\Contracts\TermsAndConditionRepositoryInterface;



class TermsAndConditionService extends BaseService implements TermsAndConditionServiceInterface
{
    protected $repository;

    public function __construct(TermsAndConditionRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to TermsAndCondition here
}
