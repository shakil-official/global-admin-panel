<?php

namespace Modules\Product\Repositories\Eloquent;

use Modules\Product\Models\Product;
use Modules\Product\Repositories\Contracts\ProductRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Product::class); // Assuming your model is named Product
    }

    // You can add custom methods specific to Product here
}
