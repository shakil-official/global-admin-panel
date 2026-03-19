<?php

namespace Modules\Blog\Repositories\Contracts;

use App\Engine\Base\Repositories\BaseRepositoryInterface;

interface BlogRepositoryInterface extends BaseRepositoryInterface
{
    // Add any custom methods specific to the Blog repository
    public function findBySlug(string $slug);
}
