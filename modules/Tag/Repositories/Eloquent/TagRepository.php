<?php

namespace Modules\Tag\Repositories\Eloquent;

use Modules\Tag\Models\Tag;
use Modules\Tag\Repositories\Contracts\TagRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;

class TagRepository extends BaseRepository implements TagRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Tag::class); // Assuming your model is named Tag
    }

    // You can add custom methods specific to Tag here
}
