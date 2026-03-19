<?php

namespace Modules\Blog\Services;

use App\Engine\Base\Services\BaseService;
use Modules\Blog\Services\Contracts\BlogServiceInterface;
use Modules\Blog\Repositories\Contracts\BlogRepositoryInterface;


class BlogService extends BaseService implements BlogServiceInterface
{
    protected $repository;

    public function __construct(BlogRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to Blog here
    
    public function findBySlug(string $slug)
    {
        return $this->repository->findBySlug($slug);
    }
}
