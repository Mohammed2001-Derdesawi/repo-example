<?php

namespace App\Http\Controllers\Api\DevelopmentalSetting;

use App\Http\Controllers\Controller;
use App\Http\Traits\Responser;
use App\Repository\DevelopmentSettingRepositoryInterface;

class DevelopmentalSettingController extends Controller
{
    use Responser;

    private DevelopmentSettingRepositoryInterface $developmentSettingRepository;

    public function __construct(
        DevelopmentSettingRepositoryInterface $developmentSettingRepository,
    )
    {
        $this->developmentSettingRepository = $developmentSettingRepository;
    }

    public function __invoke($key) {
        $setting = $this->developmentSettingRepository->first('key', $key);
        if ($setting !== null)
            return $this->responseSuccess(data: [$setting->key => $setting->value]);

        return $this->responseFail(message: 'Key not found.');
    }
}
