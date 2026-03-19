<?php

namespace Modules\Package\Repositories\Eloquent;

use Modules\Package\Models\Package;
use Modules\Package\Repositories\Contracts\PackageRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;

class PackageRepository extends BaseRepository implements PackageRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Package::class); // Assuming your model is named Package
    }

    // You can add custom methods specific to Package here
}
