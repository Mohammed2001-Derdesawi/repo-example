<?php

namespace App\Http\Controllers\Dashboard\Subscriptions;

use App\Models\Cart;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Contracts\Exportable;
use App\Http\Controllers\Controller;
use App\Http\Services\Mutual\ExportService;
use App\Repository\FieldRepositoryInterface;
use App\Repository\CourseSubscriptionRepositoryInterface;
use App\Http\Services\Dashboard\Subscriptions\SubscriptionService;
use App\Http\Requests\Dashboard\Subscriptions\AddTrialSubscriptionRequest;
use Illuminate\Database\Eloquent\Builder;
class CourseSubscriptionController extends Controller implements Exportable
{
    private CourseSubscriptionRepositoryInterface $courseSubscriptionRepository;
    private FieldRepositoryInterface $fieldRepository;
    private SubscriptionService $subscriptionService;
    private ExportService $export;

    public function __construct(CourseSubscriptionRepositoryInterface $courseSubscriptionRepository, FieldRepositoryInterface $fieldRepository , SubscriptionService $subscriptionService, ExportService $exportService){
        $this->courseSubscriptionRepository = $courseSubscriptionRepository;
        $this->subscriptionService = $subscriptionService;
        $this->fieldRepository = $fieldRepository;
        $this->middleware('permission:payments-update')->only('toggleActivate' , 'addTrial');
        $this->middleware('permission:payments-read')->only('index');
        $this->export = $exportService;
    }

    public function index(){
        $subscriptions = $this->courseSubscriptionRepository->paginate(relations : ['course' , 'user'] ,orderBy: "DESC" , filters: function ($query){
            if (\request()->has('search') && \request('search') != "" ){
                $query->where(function ($query){
                    $query->whereHas('user' , function ($query){
                        $query->where('name' , 'LIKE' , '%' . \request('search') . '%')->orWhere('email' , 'LIKE' , '%' .\request('search') . '%')
                            ->orWhere('phone' , 'LIKE' , '%' . \request('search') . '%');
                    });
                });
            }
            if (\request()->has('course') && \request('course') != "ALL"){
                $query->where('course_id' , \request('course'));
            }

            if(request()->has('register_by') && in_array(request('register_by'),$this->courseSubscriptionRepository->getAllowedRegisterMethod())){
                $query->paymentMethod();
            }

            if (\request()->has('from_date') && \request('from_date') != ""){
                $query->whereDate('created_at' , '>=' , \request('from_date'));
            }
            if (\request()->has('to_date') && \request('to_date') != ""){
                $query->whereDate('created_at' , '<=' , \request('to_date'));
            }
        });
        $fields = $this->fieldRepository->getAll(['id' , 'name_ar' , 'name_en']);
        return view('dashboard.site.course_subscriptions.index' ,  [
            'subscriptions' => $subscriptions,
            'fields' => $fields
        ]);
    }

    public function toggleActivate($id){
       return $this->subscriptionService->toggleActivate($id);
    }

    public function addTrial(AddTrialSubscriptionRequest $request){
        return $this->subscriptionService->addTrial($request);
    }

    public function export(string $type) {
        $subscriptions = $this->courseSubscriptionRepository->getAll(relations : ['course' , 'user','payment'=>function($q){
            $q->whereHasMorph('payable' , Cart::class , function (Builder $query){
                $query->withTrashed();
            });
        }] ,orderBy: "DESC" , filters: function ($query){
            if (\request()->has('search') && \request('search') != "" ){
                $query->where(function ($query){
                    $query->whereHas('user' , function ($query){
                        $query->where('name' , 'LIKE' , '%' . \request('search') . '%')->orWhere('email' , 'LIKE' , '%' .\request('search') . '%')
                            ->orWhere('phone' , 'LIKE' , '%' . \request('search') . '%');
                    });
                });
            }
            if (\request()->has('course') && \request('course') != "ALL"){
                $query->where('course_id' , \request('course'));
            }

            if (\request()->has('from_date') && \request('from_date') != ""){
                $query->whereDate('created_at' , '>=' , \request('from_date'));
            }
            if (\request()->has('to_date') && \request('to_date') != ""){
                $query->whereDate('created_at' , '<=' , \request('to_date'));
            }
        });

        $data = [
            'subscriptions' => $subscriptions,
        ];

        return $this->export->handle('subscriptions', 'dashboard.site.course_subscriptions.export', $data, 'subscriptions', $type);
    }

    public function destroy($id){
        return $this->subscriptionService->delete($id);
    }

}
