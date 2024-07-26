<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasUuids;

    protected $guarded = [];
    protected $appends = ['questions_type'];

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function standard(){
        return $this->belongsTo(Standard::class);
    }

    public function lecture(){
        return $this->belongsTo(Lecture::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function users() {
        return $this->belongsToMany(User::class);
    }

    public function exams() {
        return $this->hasMany(ExamUser::class);
    }

    public function questionsType() : Attribute {
        return Attribute::get(function ($value) {
            return match ($this->type) {
                'LECTURE' => null,
                'STANDARD' => 'indicator',
                'COURSE' => 'standard',
            };
        });
    }
}
