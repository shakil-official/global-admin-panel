<?php

namespace App\Engine\TermsAndCondition\Repositories\Eloquent;

use App\Engine\TermsAndCondition\Repositories\Contracts\TermsAndConditionRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;
use App\Models\TermsAndCondition;

class TermsAndConditionRepository extends BaseRepository implements TermsAndConditionRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(TermsAndCondition::class); // Assuming your model is named TermsAndCondition
    }

    // You can add custom methods specific to TermsAndCondition here
}
