<?php

namespace App\Console\Commands;
use App\Models\CourseUser;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CheckEndSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:endcourse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if course expire from specific user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
         CourseUser::where('is_active',1)->where('end_subscribe','!=',null)->chunk(50,function($usercourses){
            foreach($usercourses as $usercourses)
        {
            if($usercourses->end_subscribe <= Carbon::now()->toDateString())
            {
                $usercourses->delete();
            }
        }
        });

        return Command::SUCCESS;
    }
}
