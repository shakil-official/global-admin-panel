<?php

namespace Modules\Faq\Repositories\Eloquent;

use Modules\Faq\Models\Faq;
use Modules\Faq\Repositories\Contracts\FaqRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;

class FaqRepository extends BaseRepository implements FaqRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Faq::class); // Assuming your model is named Faq
    }

    // You can add custom methods specific to Faq here
}
