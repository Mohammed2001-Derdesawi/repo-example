<?php

namespace App\Repository\Eloquent;

use App\Models\DevelopmentSetting;
use App\Repository\DevelopmentSettingRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class DevelopmentSettingRepository extends Repository implements DevelopmentSettingRepositoryInterface
{
    protected Model $model;

    public function __construct(DevelopmentSetting $model)
    {
        parent::__construct($model);
    }
}
