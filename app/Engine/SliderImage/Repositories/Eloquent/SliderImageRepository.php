<?php

namespace App\Engine\SliderImage\Repositories\Eloquent;

use App\Engine\SliderImage\Repositories\Contracts\SliderImageRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;
use App\Models\SliderImage;

class SliderImageRepository extends BaseRepository implements SliderImageRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(SliderImage::class); // Assuming your model is named SliderImage
    }

    // You can add custom methods specific to SliderImage here
}
