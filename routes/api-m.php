<?php



// some routes are deleted


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Bank\BankController;
use App\Http\Controllers\Api\Cart\CartController;
use App\Http\Controllers\Api\Exam\ExamController;
use App\Http\Controllers\Api\Course\CourseController;
use App\Http\Controllers\Api\Structure\HomeController;
use App\Http\Controllers\Api\BookStore\BooksController;
use App\Http\Controllers\Api\Lecture\LectureController;
use App\Http\Controllers\Api\Manager\ManagerController;
use App\Http\Controllers\Api\Payment\PaymentController;
use App\Http\Controllers\Api\Profile\ProfileController;
use App\Http\Controllers\Api\Version\VersionController;
use App\Http\Controllers\Api\Contacts\ContactController;
use App\Http\Controllers\Api\Structure\AboutUsController;
use App\Http\Controllers\Api\Structure\ContactUsController;
use App\Http\Controllers\Api\DeviceToken\DeviceTokenController;
use App\Http\Controllers\Api\Structure\PrivacyPolicyController;
use App\Http\Controllers\Api\Notification\NotificationController;
use App\Http\Controllers\Api\Structure\CommonQuestionsController;
use App\Http\Controllers\Api\Structure\TermsConditionsController;
use App\Http\Controllers\Api\CategoryField\CategoryFieldController;
use App\Http\Controllers\Api\PointsCalculation\PointsCalculationController;
use App\Http\Controllers\Api\DevelopmentalSetting\DevelopmentalSettingController;

Route::group(['middleware' => 'localizeApi'], function () {





    // Testing

    Route::controller(CourseController::class)
        ->prefix('courses')
        ->group(function (){
            Route::get('/' , 'filter');
            Route::get('/important' , 'importantCourses');
            Route::get('/{id}/check' , 'checkSubscribe');
            Route::get('/{id}' , 'show');
            Route::get('/{id}/unSubscribedExams' , 'unSubscribedExams');
            Route::get('/{id}/subscribed' , 'showSubscribed');
            Route::get('/{id}/subscribed/about' , 'showAbout');
            Route::get('{id}/books'  , 'getCourseBooks');
            Route::get('{id}/attachments'  , 'getCourseAttachments');
            Route::get('{id}/exams'  , 'getCourseExams');
            Route::get('{id}/channels'  , 'getCourseChannels');
            Route::get('{id}/inquiries'  , 'getCourseInquiries');
            Route::get('{id}/solutions'  , 'getCourseBooksSolutions');
            Route::get('{id}/common-questions'  , 'getCommonQuestions');
            Route::post('{id}/ask'  , 'askQuestion');
            Route::post('certificate/request', 'requestCertificate');
            Route::post('rate', 'rateCourse');
            Route::get('{id}/what-to-show', 'whatToShow');
        });







    Route::controller(CartController::class)
        ->prefix('cart')
        ->group(function () {
            Route::get('/', 'show');
            Route::post('add', 'add');
            Route::post('remove', 'remove');
            Route::post('apply-coupon', 'applyCoupon');
        });

    Route::controller(PaymentController::class)
        ->prefix('payment')
        ->group(function () {
            Route::post('initiate', 'initiate');
            Route::post('webhook', 'ePaymentWebhook')->name('m.payment.webhook')->withoutMiddleware('auth:api');
            Route::post('callback', 'ePaymentCallback')->withoutMiddleware('auth:api');
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




    // get Mobile Current Version
    Route::controller(VersionController::class)
    ->prefix('versions')
    ->group(function(){
        Route::get('/current','currentVersion');
    });
});



