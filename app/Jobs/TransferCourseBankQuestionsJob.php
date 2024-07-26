<?php

namespace App\Jobs;

use Throwable;
use App\Models\Course;
use App\Models\CourseBankQuestion;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Repository\Eloquent\QuestionRepository;
use App\Repository\QuestionRepositoryInterface;
use App\Repository\CourseBankQuestionRepositoryInterface;
use App\Repository\Eloquent\CourseBankQuestionRepository;

class TransferCourseBankQuestionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $from_course_id;
    public $to_course_id;
    public CourseBankQuestionRepositoryInterface $questionRepository;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($from_course_id,$to_course_id)
    {
        $this->from_course_id=$from_course_id;
        $this->to_course_id=$to_course_id;
        $this->questionRepository=app(CourseBankQuestionRepositoryInterface::class);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::beginTransaction();
        try{
         $questions = $this->questionRepository->getBankQuestionsCourseByTransfer($this->from_course_id,$this->to_course_id,params:['id','content','course_id','is_active']);
         if(count($questions)){
             foreach($questions as $question){
                 $data['course_id']=$this->to_course_id;
                 $data['content']=$question->content;
                 $data['is_active']=$question->is_active;
                 $createdQuestion=$this->questionRepository->create($data);
                 if($question->answers()->count() > 0){
                     $answers=[];
                     foreach($question->answers()->get() as $answer){
                         $answers[]=[
                             'content'=>$answer->content,
                             'comment'=>$answer->comment,
                             'is_correct'=>$answer->is_correct?true:false
                         ];
                     }
                 $createdQuestion->answers()->createMany($answers);
                 }
                 $question->transfersFromQuestions()->create([
                     'from_course_id'=>$this->from_course_id,
                     'to_course_id'=>$this->to_course_id,
                 ]);
             }
             DB::commit();
         }
        }catch(Throwable $e){
         Log::error('Fail to Transfer Questions '. $e->getMessage());
         DB::rollBack();
         return redirect()->back()->with(['error' => __('messages.Something went wrong')]);
     }
    }
}
