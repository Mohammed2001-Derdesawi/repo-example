<?php

namespace App\Http\Resources\Course\Mobile;

use Alkoumi\LaravelHijriDate\Hijri;
use App\Http\Resources\Course\CourseCommonQuestionsResource;
use App\Http\Resources\Course\CourseGoalResource;
use App\Http\Resources\Course\CourseTeacherResource;
use App\Http\Resources\Course\CourseUnsubscribedExamResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseNotSubscribedResource extends JsonResource
{

    public function toArray($request)
    {
        Hijri::setLang(app()->getLocale());
        return [
            'name' => $this->t('name'),
            'image' => $this->image,
            'price' => $this->getRawOriginal('price'),
            'app_price' => $this->app_price,
            'registration_status' => $this->registration_status,
            'explanation_video_platform' => $this->explanation_video_platform,
            'explanation_video' => $this->explanation_video,
            'profile_file' => $this->profile_file,
            'start_date' => Hijri::MediumDate($this->start_date),
            'end_date' => $this->end_date ? Hijri::MediumDate($this->end_date) : null,
            'duration' => $this->duration ?? null,
            'rating' => $this->rating(),
            'certificate' => isset($this->certificate_price) && $this->is_certificate_avaliable,
            'certificate_price' =>$this->is_certificate_avaliable? $this->certificate_price:null,
            'teachers' => CourseTeacherResource::collection($this->teachers),
            'goals' => CourseGoalResource::collection((array)json_decode($this->goals)),
            'lectures' => CourseLecturesResource::collection($this->lectures()->watchable()->get()),
            'exams' => CourseUnsubscribedExamResource::collection($this->exams->where('is_active', true)->where('type', '!=', 'LECTURE')),
            'common_questions' => CourseCommonQuestionsResource::collection($this->category->field->common_questions ?? [])
        ];
    }
}
