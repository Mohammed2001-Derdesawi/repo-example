<?php

namespace App\Http\Services\Api\Notification;
use App\Http\Traits\Responser;
use App\Http\Services\Mutual\GetService;
use App\Http\Resources\Notification\Mobile\NotificationResource;
use App\Repository\NotificationRepositoryInterface;
use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    use Responser;
    protected NotificationRepositoryInterface $notificationRepository;
    protected GetService $get;

    public function __construct(NotificationRepositoryInterface $notificationRepository, GetService $get)
    {
        $this->notificationRepository = $notificationRepository;
        $this->get = $get;
    }

   public function getNotificationForUser()
   {
        return $this->get->handle(NotificationResource::class , $this->notificationRepository , 'getNotificationForUser',[\auth('api')->user()->id],false);
   }
}
