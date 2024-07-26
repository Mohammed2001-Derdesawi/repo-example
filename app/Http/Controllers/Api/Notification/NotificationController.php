<?php

namespace App\Http\Controllers\Api\Notification;

use App\Http\Controllers\Controller;
use App\Http\Services\Api\Notification\NotificationService;
use App\Http\Traits\Responser;
use App\Repository\NotificationRepositoryInterface;

class NotificationController extends Controller
{
    use Responser;
    private NotificationService $notificationService;
    private NotificationRepositoryInterface $notificationRepository;

    public function __construct(NotificationService $notificationService,NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationService = $notificationService;
        $this->notificationRepository = $notificationRepository;
    }

    public function getNotificationForUser()
    {
        return $this->notificationService->getNotificationForUser();
    }
}
