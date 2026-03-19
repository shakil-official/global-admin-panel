<?php

namespace Modules\Coverage\Repositories\Eloquent;

use Modules\Coverage\Models\Coverage;
use Modules\Coverage\Repositories\Contracts\CoverageRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;

class CoverageRepository extends BaseRepository implements CoverageRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Coverage::class); // Assuming your model is named Coverage
    }

    // You can add custom methods specific to Coverage here
}
