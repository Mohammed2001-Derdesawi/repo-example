<?php

// some routes are deleted

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Bank\BankController;
use App\Http\Controllers\Api\BookStore\BooksController;
use App\Http\Controllers\Api\Cart\CartController;
use App\Http\Controllers\Api\CategoryField\CategoryFieldController;
use App\Http\Controllers\Api\Contacts\ContactController;
use App\Http\Controllers\Api\Course\CourseController;
use App\Http\Controllers\Api\DevelopmentalSetting\DevelopmentalSettingController;
use App\Http\Controllers\Api\Exam\ExamController;
use App\Http\Controllers\Api\Info\InfoController;
use App\Http\Controllers\Api\Lecture\LectureController;
use App\Http\Controllers\Api\Manager\ManagerController;
use App\Http\Controllers\Api\Payment\PaymentController;
use App\Http\Controllers\Api\PointsCalculation\PointsCalculationController;
use App\Http\Controllers\Api\Profile\ProfileController;
use App\Http\Controllers\Api\Structure\AboutUsController;
use App\Http\Controllers\Api\Structure\CommonQuestionsController;
use App\Http\Controllers\Api\Structure\ContactUsController;
use App\Http\Controllers\Api\Structure\HomeController;
use App\Http\Controllers\Api\Structure\PrivacyPolicyController;
use App\Http\Controllers\Api\Structure\TermsConditionsController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'localizeApi'], function () {



    Route::controller(ExamController::class)
        ->prefix('exams')
        ->group(function () {
            Route::get('past', 'past');
            Route::get('attempts/{exam_id}', 'examAttempts');
            Route::post('start', 'start');
            Route::post('perform', 'perform');
            Route::post('end', 'end');
            Route::prefix('show')->group(function () {
                Route::post('correction', 'show');
                Route::post('result', 'result');
            });
        });

    Route::controller(CourseController::class)
        ->prefix('courses')
        ->group(function (){
            Route::get('/' , 'filter');
            Route::get('/important' , 'importantCourses');
            Route::get('/{id}/check' , 'checkSubscribe');
            Route::get('/{id}' , 'show');
            Route::get('/{id}/subscribed' , 'showSubscribed');
            Route::get('{id}/books'  , 'getCourseBooks');
            Route::get('{id}/attachments'  , 'getCourseAttachments');
            Route::get('{id}/exams'  , 'getCourseExams');
            Route::get('{id}/exams/{type}'  , 'getCourseExamsByType');
            Route::get('{id}/channels'  , 'getCourseChannels');
            Route::get('{id}/inquiries'  , 'getCourseInquiries');
            Route::get('{id}/solutions'  , 'getCourseBooksSolutions');
            Route::get('{id}/common-questions'  , 'getCommonQuestions');
            Route::post('{id}/ask'  , 'askQuestion');
            Route::post('certificate/request', 'requestCertificate');
            Route::post('rate', 'rateCourse');
            Route::prefix('embed')->group(function () {
                Route::post('lecture', 'embedLecture');
                Route::post('introductory-video', 'embedIntroductoryVideo');
                Route::post('exam-solution-video', 'embedExamIntroductoryVideo');
                Route::post('book-solution-video', 'embedBookSolutionVideo');
            });
        });





    Route::controller(CartController::class)
        ->prefix('cart')
        ->group(function () {
            Route::get('/', 'show');
            Route::post('add', 'add');
            Route::post('remove', 'remove');
            Route::post('apply-coupon', 'applyCoupon');
        });



    Route::group(['prefix' => 'structure'], function () {
        Route::get('home', HomeController::class);
        Route::get('privacy' , PrivacyPolicyController::class);
        Route::get('questions' , CommonQuestionsController::class);
        Route::get('contact-us' , ContactUsController::class);
        Route::get('about-us' , AboutUsController::class);
        Route::get('terms-conditions' , TermsConditionsController::class);
    });

    Route::controller(PointsCalculationController::class)
        ->prefix('points/calculate')
        ->group(function () {
            Route::post('differential', 'differential');
            Route::post('metrical', 'metrical');
        });


    Route::get('infos/{info}', [InfoController::class, 'get']);
});


