<?php

namespace App\Jobs;

use App\Http\Services\Api\WhatsApp\WhatsAppServiceInterface;
use App\Models\User;
use App\Repository\UserRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWhatsupMessageForUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $usersIds;
    public $message;
    public $userRepository;
    public $whatsUpService;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($usersIds,$message,)
    {
        $this->usersIds = $usersIds;
        $this->message = $message;
        $this->userRepository=app(UserRepositoryInterface::class);
        $this->whatsUpService=app(WhatsAppServiceInterface::class);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $options=[
            'to'=>null,
            'message'=>$this->message
        ];
        foreach ($this->usersIds as $userId) {
            $user=$this->userRepository->getById($userId,['id','phone']);
            $options['to']=$user->phone;
            if($this->whatsUpService->sendMessage($options)){
                $user->cart?->update(['has_send_message'=>true]);
            }

        }
        return true;
    }
}
