<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NotificationBatchCreateJob implements ShouldQueue
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
    public function __construct(array $usersIds, string $title, string $content=null, string $type=null, string $typecontent=null, bool $subscribe=false)
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
        $chunkSize = 500; // Adjust the chunk size as needed
        $chunks = array_chunk($this->usersIds, $chunkSize);

        foreach ($chunks as $chunk) {
            NotificationCreateJob::dispatch($chunk, $this->title, $this->content, $this->type, $this->typecontent, $this->subscribe);
        }
    }
}
