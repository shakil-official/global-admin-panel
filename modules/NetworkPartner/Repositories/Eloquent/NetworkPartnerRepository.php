<?php

namespace Modules\NetworkPartner\Repositories\Eloquent;

use Modules\NetworkPartner\Models\NetworkPartner;
use Modules\NetworkPartner\Repositories\Contracts\NetworkPartnerRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;

class NetworkPartnerRepository extends BaseRepository implements NetworkPartnerRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(NetworkPartner::class); // Assuming your model is named NetworkPartner
    }

    // You can add custom methods specific to NetworkPartner here
}
