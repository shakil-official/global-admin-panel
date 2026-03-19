<?php

namespace Modules\Branch\Repositories\Eloquent;

use Modules\Branch\Models\Branch;
use Modules\Branch\Repositories\Contracts\BranchRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;

class BranchRepository extends BaseRepository implements BranchRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Branch::class); // Assuming your model is named Branch
    }

    // You can add custom methods specific to Branch here
}
