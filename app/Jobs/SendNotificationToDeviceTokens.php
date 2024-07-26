<?php

namespace App\Jobs;

use App\Http\Traits\NotificationManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationToDeviceTokens implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use NotificationManager;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $devicetokens;
    public $title;
    public $content;
    public $usersIds;
    public $type;
    public $typecontent;
    public $subscribe;
    public function __construct($devicetokens,$title,$content,$usersIds=[],$type=null,$typecontent=null,$subscribe=false)
    {
        $this->devicetokens=$devicetokens;
        $this->title=$title;
        $this->content=$content;
        $this->usersIds=$usersIds;
        $this->type=$type;
        $this->typecontent=$typecontent;
        $this->subscribe=$subscribe;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach(array_chunk($this->devicetokens,700) as $tokens){
                $this->notify($tokens,$this->title,$this->content,$this->usersIds,$this->type,$this->typecontent?->id,$this->subscribe);
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
