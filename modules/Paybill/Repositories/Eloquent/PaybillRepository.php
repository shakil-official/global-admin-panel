<?php

namespace Modules\Paybill\Repositories\Eloquent;


use Modules\Paybill\Models\PayBill;
use Modules\Paybill\Repositories\Contracts\PaybillRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;

class PaybillRepository extends BaseRepository implements PaybillRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(PayBill::class); // Assuming your model is named Paybill
    }

    // You can add custom methods specific to Paybill here
}
