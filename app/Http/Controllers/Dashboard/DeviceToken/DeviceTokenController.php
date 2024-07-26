<?php

namespace App\Http\Controllers\Dashboard\DeviceToken;
use App\Http\Services\Dashboard\DeviceToken\DeviceTokenService;
use App\Http\Controllers\Controller;
use App\Http\Services\Mutual\ExportService;
use App\Repository\DeviceTokenRepositoryInterface;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    private DeviceTokenRepositoryInterface $devicetokenRepository;
    private ExportService $export;
    private DeviceTokenService $devicetokenService;

    public function __construct(DeviceTokenRepositoryInterface $devicetokenRepository , ExportService $export , DeviceTokenService $devicetokenService)
    {
        $this->devicetokenRepository = $devicetokenRepository;
        $this->export = $export;
        $this->devicetokenService = $devicetokenService;
    }

    public function edit()
    {
        return $this->devicetokenService->edit();
    }

    public function sendsubscribe(Request $request)
    {
        return $this->devicetokenService->sendsubscribe($request);
    }

    public function sendnotsubscribe(Request $request)
    {
        return $this->devicetokenService->sendnotsubscribe($request);
    }

    public function sendnotregister(Request $request)
    {
        return $this->devicetokenService->sendnotregister($request);
    }

}
