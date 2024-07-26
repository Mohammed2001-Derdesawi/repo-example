<?php

namespace App\Jobs;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NotificationCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $usersIds;
    protected $title;
    protected $content;
    protected $type;
    protected $typecontent;
    protected $subscribe;

    /**
     * Create a new job instance.
     *
     * @param array $usersIds
     * @param string $title
     * @param string $content
     * @param string $type
     * @param string $typecontent
     * @param bool $subscribe
     */
    public function __construct(array $usersIds, string $title, string $content, string $type, string $typecontent, bool $subscribe)
    {
        $this->usersIds = $usersIds;
        $this->title = $title;
        $this->content = $content;
        $this->type = $type;
        $this->typecontent = $typecontent;
        $this->subscribe = $subscribe;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->usersIds as $userId) {
            Notification::create([
                'user_id'       => $userId,
                'title'         => $this->title,
                'body'          => $this->content,
                'type'          => $this->type,
                'content'       => $this->typecontent,
                'is_subscribe'  => $this->subscribe,
            ]);
        }
    }
}
