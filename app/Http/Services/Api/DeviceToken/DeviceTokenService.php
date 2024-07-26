<?php

namespace App\Http\Services\Api\DeviceToken;
use App\Http\Requests\Api\DeviceToken\DeviceTokenRequest;
use App\Http\Traits\Responser;
use App\Repository\DeviceTokenRepositoryInterface;
use App\Models\DeviceToken;


class DeviceTokenService
{
    use Responser;
    protected DeviceTokenRepositoryInterface $devicetokenRepository;

    public function __construct(DeviceTokenRepositoryInterface $devicetokenRepository)
    {
        $this->devicetokenRepository = $devicetokenRepository;
    }

   public function devicetoken(DeviceTokenRequest $request)
   {
        DeviceToken::query()->updateOrCreate(['token' => $request->fcm_token]);
       return $this->responseSuccess(200 , __('messages.created successfully'));
   }
}
