<?php

namespace Modules\Slider\Repositories\Eloquent;

use Modules\Slider\Models\Slider;
use Modules\Slider\Repositories\Contracts\SliderRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;

class SliderRepository extends BaseRepository implements SliderRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Slider::class); // Assuming your model is named Slider
    }

    // You can add custom methods specific to Slider here
}
