<?php

namespace App\Engine\Contact\Repositories\Eloquent;

use App\Engine\Contact\Repositories\Contracts\ContactRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;
use App\Models\Contact;

class ContactRepository extends BaseRepository implements ContactRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Contact::class); // Assuming your model is named Contact
    }

    // You can add custom methods specific to Contact here
}
