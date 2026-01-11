<?php

namespace App\Engine\Feedback\Services;

use App\Engine\Base\Services\BaseService;
use App\Engine\Feedback\Services\Contracts\FeedbackServiceInterface;
use App\Engine\Feedback\Repositories\Contracts\FeedbackRepositoryInterface;



class FeedbackService extends BaseService implements FeedbackServiceInterface
{
    protected $repository;

    public function __construct(FeedbackRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to Feedback here
}
