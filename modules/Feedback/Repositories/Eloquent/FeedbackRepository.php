<?php

namespace Modules\Feedback\Repositories\Eloquent;

use Modules\Feedback\Models\Feedback;
use Modules\Feedback\Repositories\Contracts\FeedbackRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;

class FeedbackRepository extends BaseRepository implements FeedbackRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Feedback::class); // Assuming your model is named Feedback
    }

    // You can add custom methods specific to Feedback here
}
