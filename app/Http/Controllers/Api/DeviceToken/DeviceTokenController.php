<?php

namespace App\Http\Controllers\Api\DeviceToken;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DeviceToken\DeviceTokenRequest;
use App\Http\Services\Api\DeviceToken\DeviceTokenService;
use App\Http\Traits\Responser;
use App\Repository\DeviceTokenRepositoryInterface;

class DeviceTokenController extends Controller
{
    use Responser;
    private DeviceTokenService $devicetokenService;

    public function __construct(DeviceTokenService $devicetokenService)
    {
        $this->devicetokenService = $devicetokenService;
    }

    public function devicetoken(DeviceTokenRequest $request)
    {
        return $this->devicetokenService->devicetoken($request);
    }
}
