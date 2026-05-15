<?php

namespace Modules\General\Repositories\Eloquent;

use Modules\General\Models\General;
use Modules\General\Repositories\Contracts\GeneralRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;

class GeneralRepository extends BaseRepository implements GeneralRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(General::class); // Assuming your model is named General
    }

    // You can add custom methods specific to General here
}
