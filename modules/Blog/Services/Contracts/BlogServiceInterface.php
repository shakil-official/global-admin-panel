<?php

namespace Modules\Blog\Services\Contracts;

use App\Engine\Base\Services\BaseServiceInterface;

interface BlogServiceInterface extends BaseServiceInterface
{
    // Add any custom methods specific to the Blog service
    public function findBySlug(string $slug);
}
