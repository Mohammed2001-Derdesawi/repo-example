<?php

namespace App\Providers;

use App\Http\Services\Api\Auth\AuthService;
use App\Http\Services\Api\Auth\MobileAuthService;
use App\Http\Services\Api\Auth\WebAuthService;
use App\Http\Services\Api\Bank\BankService;
use App\Http\Services\Api\Bank\MobileBankService;
use App\Http\Services\Api\Bank\WebBankService;
use App\Http\Services\Api\Book\BookService;
use App\Http\Services\Api\Book\MobileBookService;
use App\Http\Services\Api\Book\WebBookService;
use App\Http\Services\Api\Cart\CartService;
use App\Http\Services\Api\Cart\MobileCartService;
use App\Http\Services\Api\Cart\WebCartService;
use App\Http\Services\Api\CategoryField\CategoryFieldService;
use App\Http\Services\Api\CategoryField\MobileCategoryFieldService;
use App\Http\Services\Api\CategoryField\WebCategoryFieldService;
use App\Http\Services\Api\Certificate\CertificateService;
use App\Http\Services\Api\Certificate\MobileCertificateService;
use App\Http\Services\Api\Certificate\WebCertificateService;
use App\Http\Services\Api\Contact\ContactService;
use App\Http\Services\Api\Contact\MobileContactService;
use App\Http\Services\Api\Contact\WebContactService;
use App\Http\Services\Api\Course\CourseService;
use App\Http\Services\Api\Course\MobileCourseService;
use App\Http\Services\Api\Course\WebCourseService;
use App\Http\Services\Api\Exam\ExamService;
use App\Http\Services\Api\Exam\MobileExamService;
use App\Http\Services\Api\Exam\WebExamService;
use App\Http\Services\Api\Lecture\LectureService;
use App\Http\Services\Api\Lecture\MobileLectureService;
use App\Http\Services\Api\Lecture\WebLectureService;
use App\Http\Services\Api\Manager\ManagerService;
use App\Http\Services\Api\Manager\MobileManagerService;
use App\Http\Services\Api\Manager\WebManagerService;
use App\Http\Services\Api\Payment\MobilePaymentService;
use App\Http\Services\Api\Payment\PaymentService;
use App\Http\Services\Api\Payment\WebPaymentService;
use App\Http\Services\Api\PointsCalculation\DifferentialPointsCalculationService;
use App\Http\Services\Api\PointsCalculation\MetricalPointsCalculationService;
use App\Http\Services\Api\PointsCalculation\MobileDifferentialPointsCalculationService;
use App\Http\Services\Api\PointsCalculation\MobileMetricalPointsCalculationService;
use App\Http\Services\Api\PointsCalculation\WebDifferentialPointsCalculationService;
use App\Http\Services\Api\PointsCalculation\WebMetricalPointsCalculationService;
use App\Http\Services\Api\Profile\MobileProfileService;
use App\Http\Services\Api\Profile\ProfileService;
use App\Http\Services\Api\Profile\WebProfileService;
use App\Http\Services\Api\Structure\MobileStructureService;
use App\Http\Services\Api\Structure\StructureService;
use App\Http\Services\Api\Structure\WebStructureService;
use App\Http\Services\Api\DeviceToken\DeviceTokenService;
use App\Http\Services\Api\OTO\OTOService;
use App\Http\Services\Api\OTO\OTOServiceInterface;
use App\Http\Services\Api\WhatsApp\WhatsAppService;
use App\Http\Services\Api\WhatsApp\WhatsAppServiceInterface;
use Illuminate\Support\ServiceProvider;

class PlatformServiceProvider extends ServiceProvider
{

    public function detectPlatform($webService , $mobileService){
        if (request()->is('api/w/*') || request()->is('w/*'))
            return $webService;
       return $mobileService;
    }

    public function register()
    {
        $this->app->singleton(StructureService::class , $this->detectPlatform(WebStructureService::class , MobileStructureService::class));
        $this->app->singleton(AuthService::class , $this->detectPlatform(WebAuthService::class , MobileAuthService::class));
        $this->app->singleton(CartService::class , $this->detectPlatform(WebCartService::class , MobileCartService::class));
        $this->app->singleton(CertificateService::class , $this->detectPlatform(WebCertificateService::class , MobileCertificateService::class));
        $this->app->singleton(CourseService::class , $this->detectPlatform(WebCourseService::class , MobileCourseService::class));
        $this->app->singleton(ExamService::class , $this->detectPlatform(WebExamService::class , MobileExamService::class));
        $this->app->singleton(LectureService::class , $this->detectPlatform(WebLectureService::class , MobileLectureService::class));
        $this->app->singleton(PaymentService::class , $this->detectPlatform(WebPaymentService::class , MobilePaymentService::class));
        $this->app->singleton(DifferentialPointsCalculationService::class , $this->detectPlatform(WebDifferentialPointsCalculationService::class , MobileDifferentialPointsCalculationService::class));
        $this->app->singleton(MetricalPointsCalculationService::class , $this->detectPlatform(WebMetricalPointsCalculationService::class , MobileMetricalPointsCalculationService::class));
        $this->app->singleton(ProfileService::class , $this->detectPlatform(WebProfileService::class , MobileProfileService::class));
        $this->app->singleton(BookService::class , $this->detectPlatform(WebBookService::class , MobileBookService::class));
        $this->app->singleton(CategoryFieldService::class , $this->detectPlatform(WebCategoryFieldService::class , MobileCategoryFieldService::class));
        $this->app->singleton(ContactService::class , $this->detectPlatform(WebContactService::class , MobileContactService::class));
        $this->app->singleton(ManagerService::class , $this->detectPlatform(WebManagerService::class , MobileManagerService::class));
        $this->app->singleton(BankService::class , $this->detectPlatform(WebBankService::class , MobileBankService::class));
        $this->app->bind(WhatsAppServiceInterface::class,WhatsAppService::class);
        $this->app->bind(OTOServiceInterface::class,OTOService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
