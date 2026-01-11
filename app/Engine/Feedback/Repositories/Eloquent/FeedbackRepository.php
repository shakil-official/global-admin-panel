<?php

namespace App\Engine\Feedback\Repositories\Eloquent;

use App\Engine\Feedback\Repositories\Contracts\FeedbackRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;
use App\Models\Feedback;

class FeedbackRepository extends BaseRepository implements FeedbackRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Feedback::class); // Assuming your model is named Feedback
    }

    // You can add custom methods specific to Feedback here
}
