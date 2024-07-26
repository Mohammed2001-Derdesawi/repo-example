<?php

namespace App\Http\Resources\Course;

use Alkoumi\LaravelHijriDate\Hijri;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class CourseDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        Hijri::setLang(app()->getLocale());
        return [
            'name' => $this->t('name'),
            'description' => $this->t('description'),
            'image' => $this->image,
            'is_subscribed' => $this->isSubscribed(),
            'explanation_video_platform' => $this->explanation_video_platform,
            'explanation_video' => $this->explanation_video ,
            'price' => $this->getRawOriginal('price'),
            'app_price' => $this->app_price,
            'registration_status' => $this->registration_status,
            'rating' => $this->rating(),
            'profile_file' => $this->profile_file,
            'course_hours' => $this->duration ?? null,
            'start_date' =>Hijri::MediumDate($this->start_date),
            'end_date' => $this->end_date ? Hijri::MediumDate($this->end_date) : null,
            'certificate' => isset($this->certificate_price) && $this->is_certificate_avaliable,
            'certificate_price' => $this->certificate_price,
            'show_teacher_names' => $this->show_teacher_names ,
            'teachers' => CourseTeacherResource::collection($this->teachers),
            'goals' => CourseGoalResource::collection((array)json_decode($this->goals)),
            'lectures' => CourseLectureSimpleResource::collection($this->lectures->where('is_published' , true)->where('is_active' , true)),
            'books' => CourseUnsubscribedBookResource::collection($this->books),
            'exams' => CourseUnsubscribedExamResource::collection($this->exams->where('is_active' , true)->where('type' , '!=' , 'LECTURE') )
        ];
    }


}
