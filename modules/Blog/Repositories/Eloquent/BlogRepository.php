<?php

namespace Modules\Blog\Repositories\Eloquent;

use Modules\Blog\Models\Blog;
use Modules\Blog\Repositories\Contracts\BlogRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;

class BlogRepository extends BaseRepository implements BlogRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Blog::class); // Assuming your model is named Blog
    }

    // You can add custom methods specific to Blog here

    public function findBySlug(string $slug)
    {
        return $this->getModel()->where(['slug' => $slug])->first();
    }
}
