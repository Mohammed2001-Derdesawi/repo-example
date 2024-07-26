<?php

namespace App\Repository\Eloquent;

use App\Models\DeviceToken;
use App\Repository\DeviceTokenRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class DeviceTokenRepository extends Repository implements DeviceTokenRepositoryInterface
{
    protected Model $model;

    public function __construct(DeviceToken $model)
    {
        parent::__construct($model);
    }

    public function count()
    {
        return $this->model::query()->count();
    }

}
