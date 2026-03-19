<?php

namespace Modules\Service\Repositories\Eloquent;

use Modules\Service\Models\Service;
use Modules\Service\Repositories\Contracts\ServiceRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;

class ServiceRepository extends BaseRepository implements ServiceRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Service::class); // Assuming your model is named Service
    }

    // You can add custom methods specific to Service here
}
