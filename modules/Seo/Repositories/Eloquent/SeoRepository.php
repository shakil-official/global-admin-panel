<?php

namespace Modules\Seo\Repositories\Eloquent;

use Modules\Seo\Models\Seo;
use Modules\Seo\Repositories\Contracts\SeoRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;

class SeoRepository extends BaseRepository implements SeoRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Seo::class); // Assuming your model is named Seo
    }

    // You can add custom methods specific to Seo here
}
