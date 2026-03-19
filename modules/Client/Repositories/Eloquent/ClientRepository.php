<?php

namespace Modules\Client\Repositories\Eloquent;

use Modules\Client\Models\Client;
use Modules\Client\Repositories\Contracts\ClientRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;

class ClientRepository extends BaseRepository implements ClientRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Client::class); // Assuming your model is named Client
    }

    // You can add custom methods specific to Client here
}
