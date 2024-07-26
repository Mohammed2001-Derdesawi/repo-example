<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $usersChunk;
    public $chunkSize = 500;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($usersChunk)
    {
        $this->usersChunk = $usersChunk;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//         $chunkSize = $this->chunkSize;

//         for ($i = 0; $i < count($this->usersChunk); $i += $chunkSize) {
//             $users = array_map(function ($user) {
//                 return [
//                     'name' => $user['fname'],
//                     'email' => $user['email'],
//                     'phone' => $user['phone'],
// //                    'communication_code' => $user['code'],
//                     'password' => bcrypt($user['login_psw']),
//                     'is_active' => true
//                 ];
//             }, array_slice($this->usersChunk, $i, $chunkSize));


// 			foreach($users as $user) {
// 				User::query()->firstOrCreate(
// 					['email' => $user['email']],
// 					[
// 						'name' => $user['fname'],
// 						'email' => $user['email'],
// 						'phone' => $user['phone'],
// 						'communication_code' => $user['code'],
// 						'password' => bcrypt($user['login_psw']),
// 						'is_active' => true
// 					]
// 				);
// 			}



//             //User::query()->insert($users);
//         }

//         sleep(3);
    }
}
