<?php

namespace Modules\Offer\Repositories\Eloquent;

use Modules\Offer\Models\Offer;
use Modules\Offer\Repositories\Contracts\OfferRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;

class OfferRepository extends BaseRepository implements OfferRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Offer::class); // Assuming your model is named Offer
    }

    // You can add custom methods specific to Offer here
}
