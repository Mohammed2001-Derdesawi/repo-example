<?php

namespace App\Http\Traits;

use App\Jobs\NotificationBatchCreateJob;
use App\Models\Notification;
use App\Jobs\NotificationCreateJob;
use App\Repository\UserRepositoryInterface;
use App\Repository\NotificationRepositoryInterface;

trait NotificationManager
{
    private static string $serverApiKey = 'test';

    private UserRepositoryInterface $userRepository;
    protected NotificationRepositoryInterface $notificationRepository;

    public function __construct(UserRepositoryInterface $userRepository,NotificationRepositoryInterface $notificationRepository)
    {
        $this->userRepository = $userRepository;
        $this->notificationRepository = $notificationRepository;
    }


    private function getDeviceTokens($target)
    {
        return $this->userRepository->getDeviceTokens($target);
    }

    private function notificationScheme(array $deviceTokens, string $title, $content , string $type=null, string $typecontent=null,$subscribe = false)
    {
        return json_encode([
        'registration_ids'  => $deviceTokens,
        'notification'      => [
        'title' => $title,
        'body' => $content,
        ],
        'data'  => [
        'type' => $type,
        'content' => $typecontent,
        'subscribe' => $subscribe,
            ],
        ]);
    }

    private function preparePush($deviceTokens,$title,$content,$usersIds=[], string $type=null, string $typecontent=null,$subscribe = false)
    {


        dispatch(new NotificationBatchCreateJob($usersIds,$title,$content,$type,$typecontent,$subscribe));

        // $users = $this->getDeviceTokens($request->to);

        // $usersIds = array_keys($users);
        // $usersDeviceTokens = array_values($users);
        // $notification->users()->attach($usersIds);
        return $this->notificationScheme(deviceTokens: $deviceTokens, title: $title, content: $content,type: $type, typecontent: $typecontent,subscribe: $subscribe);
    }


}
