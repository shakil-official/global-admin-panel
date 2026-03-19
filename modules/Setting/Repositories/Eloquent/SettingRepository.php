<?php

namespace Modules\Setting\Repositories\Eloquent;

use Modules\Setting\Models\Setting;
use Modules\Setting\Repositories\Contracts\SettingRepositoryInterface;
use App\Engine\Base\Repositories\BaseRepository;

class SettingRepository extends BaseRepository implements SettingRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Setting::class); // Assuming your model is named Setting
    }

    // You can add custom methods specific to Setting here
}
