<?php

namespace App\Jobs;

use App\Http\Traits\NotificationManager;
use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendFirebaseNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use NotificationManager;

    protected $title;
    protected $content;
    protected $context;
    protected $courseId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($title, $content, $context = null, $courseId = null)
    {
        $this->title = $title;
        $this->content = $content;
        $this->context = $context;
        $this->courseId = $courseId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $usersIds = User::whereDoesntHave('courses',function($query){
            if($this->courseId != null)
            {
                $query->where('courses.id',$this->courseId);
            }
        })->whereHas('devicetokens')
            ->pluck('id')
            ->toArray();
        $devicetokens=DeviceToken::whereIn('user_id', $usersIds)->pluck('token')->toArray();
        foreach(array_chunk($devicetokens,700) as $tokens){
            $this->notify($tokens, $this->title, $this->content, $usersIds, $this->context, $this->courseId);
        }
    }

    public function notify($deviceTokens,$title,$content,$usersIds=[],string $type=null, string $typecontent=null,$subscribe = false)
    {
        $notification = $this->preparePush($deviceTokens,$title,$content,$usersIds,$type,$typecontent,$subscribe);
        $headers = [
            'Authorization: key=' . static::$serverApiKey,
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $notification);
        curl_exec($ch);
        curl_close($ch);
    }

}
