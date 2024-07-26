<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class ExamUser extends Model
{
    use HasUuids, HasRelationships;

    protected $table = 'exam_user';
    protected $guarded = [];

    public function answers() {
        return $this->hasMany(AnswerUser::class);
    }

    public function orderAnswers()
    {
        return $this->answers()
            ->join('questions', 'answer_user.question_id', '=', 'questions.id')
            ->orderBy('questions.order')
            ->select('answer_user.*');
    }

    public function questions() {
        return $this->hasManyThrough(
            Question::class,
            AnswerUser::class,
            'exam_user_id',
            'id',
            'id',
            'question_id'
        );
    }

    public function standards() {
        return $this->hasManyDeep(
            Standard::class,
            [AnswerUser::class, Question::class],
            ['exam_user_id', 'id', 'id'],
            [null, 'question_id', 'standard_id']
        )->withTrashed('questions.deleted_at')->distinct();
    }


    public function indicators() {
        return $this->hasManyDeep(
            Indicator::class,
            [AnswerUser::class, Question::class],
            ['exam_user_id', 'id', 'id'],
            [null, 'question_id', 'indicator_id']
        )->withTrashed('questions.deleted_at')->distinct();
    }

    public function exam() {
        return $this->belongsTo(Exam::class);
    }

    public function scopeMy($query) {
        return $query->where('user_id', auth('api')->id())->orderByDesc('id');
    }

}
