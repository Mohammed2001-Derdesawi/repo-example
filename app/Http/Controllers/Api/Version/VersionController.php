<?php

namespace App\Http\Controllers\Api\Version;

use App\Http\Traits\Responser;
use App\Http\Controllers\Controller;
use App\Http\Services\Api\Info\InfoService;
use App\Repository\InfoRepositoryInterface;

class VersionController extends Controller {

    use Responser;
    public function currentVersion(){
        return $this->responseSuccess(data:[
            'current'=>config('versions.mobile.current_version'),
            'google_play_link'=>app(InfoRepositoryInterface::class)->getValue('google_play'),
            'apple_play_link'=>app(InfoRepositoryInterface::class)->getValue('app_store'),
            'versions'=>[
                'android_version'=>config('versions.mobile.android_version'),
                'apple_version'=>config('versions.mobile.apple_version')
            ]
        ]);
    }
}
