<?php

namespace App\Models;

use App\Http\Traits\HasIsActive;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseUser extends Model
{
    use HasIsActive;
    protected $table = 'course_user';
    protected $guarded = [];

    public function isActive() : Attribute
    {
        return Attribute::make(
            set: fn($value) => $value == 'on'
        );
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function payment(){
        return $this->belongsTo(Payment::class);
    }

    public function getDateSubscription(){
       return $this->payment?->payable()->withTrashed()->whereHas('items',function($q){
        $q->withTrashed()->where(['cartable_type'=>Course::class,'cartable_id'=>$this->course?->id]);
       })->first()?->deleted_at?->format('Y-m-d H:i:s')??null;
    }

    public function scopePaymentMethod($q){
        $method=request('register_by');
        switch($method){
            case 'cash':
                return $q->whereHas('payment',function($q){
                    return $q->where('payment_type','CASH');
                });
                break;
            case 'tamara':
                return $q->whereHas('payment',function($q){
                    return $q->where('payment_type','TAMARA');
                });
                break;
            case 'epayment':
                return $q->whereHas('payment',function($q){
                    return $q->where('payment_type','EPAYMENT');
                });
                break;
            case 'admin':
                return $q->whereDoesntHave('payment');
                break;
            default :
            return $q;
        }
    }
}
