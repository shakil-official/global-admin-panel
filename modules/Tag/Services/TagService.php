<?php

namespace Modules\Tag\Services;

use App\Engine\Base\Services\BaseService;
use Modules\Tag\Services\Contracts\TagServiceInterface;
use Modules\Tag\Repositories\Contracts\TagRepositoryInterface;


class TagService extends BaseService implements TagServiceInterface
{
    protected $repository;

    public function __construct(TagRepositoryInterface $repository)
    {
        $this->repository = $repository;

         parent::__construct($this->repository);
    }

    // Implement methods from the BaseServiceInterface here

    // You can also add custom methods specific to Tag here
}
