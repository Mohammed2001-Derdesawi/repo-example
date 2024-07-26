<?php

namespace App\Models;

use App\Http\Resources\Course\LecturePinResource;
use App\Http\Resources\Course\Web\CourseSubscribedExamResource;
use App\Http\Traits\LanguageToggle;
use App\Http\Traits\Sortable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Course extends Model
{
    use HasFactory, LanguageToggle, Sortable, HasRelationships;

    protected $guarded = [];
    protected $appends = ['user_progress'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function standards()
    {
        return $this->hasMany(Standard::class)->orderBy('sort');
    }

    public function lectures()
    {
        return $this->hasManyThrough(Lecture::class, Standard::class, 'course_id', 'standard_id');
    }

    public function pins()
    {
        return $this->hasManyDeepFromRelations($this->lectures(), (new Lecture())->pins());
    }

    public function books()
    {
        return $this->hasMany(CourseBook::class);
    }

    public function solutions()
    {
        return $this->hasMany(BookSolution::class);
    }

    public function attachments()
    {
        return $this->hasMany(CourseAttachment::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Manager::class, 'course_teacher');
    }

    public function cart()
    {
        return $this->morphOne(CartContent::class, 'cartable');
    }

    public function subscriptions()
    {
        return $this->hasMany(CourseUser::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function printRequests()
    {
        return $this->hasMany(PrintRequest::class);
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    public function price(): Attribute
    {
        return Attribute::get(function ($value) {
            return request()->is('api/m/*') || request()->is('m/*') ? $this->app_price : $value;
        });
    }

    public function image(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value !== null) {
                    return url($value);
                }
                return null;
            }
        );
    }

    public function profileFile(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value !== null) {
                    return url($value);
                }
                return null;
            }
        );
    }

    public function hasLectures() : Attribute
    {
        return Attribute::get(function () {
            return $this->lectures()->watchable()->exists();
        });
    }

    public function hasBooks() : Attribute
    {
        return Attribute::get(function () {
            return $this->books()->exists();
        });
    }

    public function hasGroups() : Attribute
    {
        return Attribute::get(function () {
            return
                $this->whatsapp_link !== null
                || $this->telegram_link !== null
                || $this->telegram_channel_link !== null;
        });
    }

    public function hasAttachments() : Attribute
    {
        return Attribute::get(function () {
            return $this->attachments()->where('is_active', true)->exists();
        });
    }

    public function hasBookSolutions() : Attribute
    {
        return Attribute::get(function () {
            return $this->solutions()->exists();
        });
    }

    public function hasAbout() : Attribute
    {
        return Attribute::get(function () {
            return $this->description_ar !== null;
        });
    }

    public function hasExams() : Attribute
    {
        return Attribute::get(function () {
            return $this->exams()->where('is_active', true)->whereIn('type', ['STANDARD', 'COURSE'])->exists();
        });
    }

//    public function explanationVideo() : Attribute {
//        return Attribute::make(
//            get: function ($value) {
//                if ($value !== null) {
//                    return url($value);
//                }
//                return null;
//            }
//        );
//    }

    public function courseContent()
    {
        $content = [];
        foreach ($this->lectures()->watchable()->get() as $lecture) {
            $content[] = [
                'type' => "LECTURE",
                'id' => $lecture->id,
                'name' => $lecture->t('name'),
                'description' => $lecture->t('description'),
                'duration' => $lecture->duration,
                'is_watched' => auth('api')?->user()->watchedLectures->contains('lecture_id', $lecture->id) ?? false,
                'lecture_type' => $lecture->type,
                'live_link' => $lecture->live_link,
                'link_platform' => $lecture->link_platform,
                'record_link' => $lecture->record_link,
                'pins' => LecturePinResource::collection($lecture->pins),
                'exams' => CourseSubscribedExamResource::collection(($lecture->exams()->where('is_active', true)->whereHas('questions', fn($query) => $query->where('is_active', true))->get()))
            ];
//            foreach ($lecture->exams()->where('is_active' , true)->get() as $exam){
//                $content[]  = [
//                    'type' => 'EXAM',
//                    'id' => $exam->id,
//                    'lecture_name' => $lecture->t('name'),
//                    'is_performed' => auth('api')?->user()->exams->contains('id', $exam->id) ,
//
//                ];
//            }
        }
        return $content;
    }

    public function newCourseContent()
    {
        $content = [];
        foreach ($this->standards()->whereHas('lectures', fn($query) => $query->watchable())->get() as $key => $standard) {
            $content[] = [
                'id' => $standard->id,
                'name' => $standard->t('name'),
            ];

            foreach ($standard->lectures()->watchable()->get() as $lecture) {
                $content[$key]['lectures'][] = [
                    'type' => "LECTURE",
                    'id' => $lecture->id,
                    'name' => $lecture->t('name'),
                    'description' => $lecture->t('description'),
                    'duration' => $lecture->duration,
                    'is_watched' => auth('api')?->user()->watchedLectures->contains('lecture_id', $lecture->id) ?? false,
                    'lecture_type' => $lecture->type,
                    'live_link' => $lecture->live_link,
                    'link_platform' => $lecture->link_platform,
                    'record_link' => $lecture->record_link,
                    'pins' => LecturePinResource::collection($lecture->pins),
                    'exams' => CourseSubscribedExamResource::collection(($lecture->exams()->where('is_active', true)->whereHas('questions', fn($query) => $query->where('is_active', true))->get()))
                ];
//            foreach ($lecture->exams()->where('is_active' , true)->get() as $exam){
//                $content[]  = [
//                    'type' => 'EXAM',
//                    'id' => $exam->id,
//                    'lecture_name' => $lecture->t('name'),
//                    'is_performed' => auth('api')?->user()->exams->contains('id', $exam->id) ,
//
//                ];
//            }
            }
        }
        return $content;
    }

    public function isSubscribed()
    {
        return (auth('api')?->user() && auth('api')?->user()?->courses?->contains('id', $this->id));
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function currentUserRating()
    {
        if (auth('api')->user()) {
            if ($this->reviews->contains('user_id', auth('api')->id())) {
                return $this->reviews()->where('user_id', auth('api')->id())->select(['message', 'rating'])->first();
            }
            return null;
        }

        return null;
    }

    public function rating()
    {
        return $this->reviews !== null ? $this->reviews()->avg('rating') : null;
    }

    public function getUserProgressAttribute()
    {
        $allLectures = $this->lectures->count();
        if ($allLectures == 0)
            return 0;
        $watched = LectureStudent::where('user_id', auth('api')->id())
            ->whereHas('lecture', function ($query) {
                $query->whereHas('standard', function ($query) {
                    $query->where('course_id', $this->id);
                });
            })->count();
        return ceil(($watched / $allLectures) * 100);
    }

    public function paymentsCount($type = null)
    {
        return $this->subscriptions()->whereHas('payment', function ($q) use ($type) {
            if ($type !== null) {
                $q->where('payment_type', $type);
            }
        })->count();
    }

    public function subscriptionsByAdminCount(){
        return $this->subscriptions()->whereDoesntHave('payment')->where('is_active',1)->count();
    }

    public function isRequestCertificate(){
        // return ;
        // if($this->price==0)
        // return true;
        return (auth('api')?->user() && auth('api')?->user()?->printRequests()->where(['type'=>'CERTIFICATE','status'=>'ORDERED','course_id'=>$this->id])->first()?true:false);
    }

}
