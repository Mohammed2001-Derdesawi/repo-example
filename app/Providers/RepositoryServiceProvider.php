<?php

namespace App\Providers;

use App\Repository\AnswerRepositoryInterface;
use App\Repository\AnswerUserRepositoryInterface;
use App\Repository\BankRepositoryInterface;
use App\Repository\BookPartRepositoryInterface;
use App\Repository\BookUserRepositoryInterface;
use App\Repository\CartContentRepositoryInterface;
use App\Repository\CartRepositoryInterface;
use App\Repository\CategoryRepositoryInterface;
use App\Repository\CertificateUserRepositoryInterface;
use App\Repository\ContactRepositoryInterface;
use App\Repository\CouponRepositoryInterface;
use App\Repository\CourseAttachmentRepositoryInterface;
use App\Repository\CourseBankQuestionAnswerRepositoryInterface;
use App\Repository\CourseBankQuestionRepositoryInterface;
use App\Repository\CourseBookRepositoryInterface;
use App\Repository\CourseBookSolutionRepositoryInterface;
use App\Repository\CourseInquireRepositoryInterface;
use App\Repository\CourseRepositoryInterface;
use App\Repository\CourseReviewRepositoryInterface;
use App\Repository\CourseUserRepositoryInterface;
use App\Repository\CourseSubscriptionRepositoryInterface;
use App\Repository\DevelopmentSettingRepositoryInterface;
use App\Repository\DeviceTokenRepositoryInterface;
use App\Repository\Eloquent\CourseBankQuestionAnswerRepository;
use App\Repository\Eloquent\CourseBankQuestionRepository;
use App\Repository\Eloquent\DeviceTokenRepository;
use App\Repository\Eloquent\AnswerRepository;
use App\Repository\Eloquent\AnswerUserRepository;
use App\Repository\Eloquent\BankRepository;
use App\Repository\Eloquent\BookPartRepository;
use App\Repository\Eloquent\BookUserRepository;
use App\Repository\Eloquent\CartContentRepository;
use App\Repository\Eloquent\CartRepository;
use App\Repository\Eloquent\CategoryRepository;
use App\Repository\Eloquent\CertificateUserRepository;
use App\Repository\Eloquent\ContactRepository;
use App\Repository\Eloquent\CouponRepository;
use App\Repository\Eloquent\CourseAttachmentRepository;
use App\Repository\Eloquent\CourseBookRepository;
use App\Repository\Eloquent\CourseBookSolutionRepository;
use App\Repository\Eloquent\CourseInquireRepository;
use App\Repository\Eloquent\CourseRepository;
use App\Repository\Eloquent\CourseReviewRepository;
use App\Repository\Eloquent\CourseUserRepository;
use App\Repository\Eloquent\CourseSubscriptionRepository;
use App\Repository\Eloquent\DevelopmentSettingRepository;
use App\Repository\Eloquent\ExamRepository;
use App\Repository\Eloquent\ExamUserRepository;
use App\Repository\Eloquent\FieldRepository;
use App\Repository\Eloquent\IndicatorRepository;
use App\Repository\Eloquent\InfoRepository;
use App\Repository\Eloquent\LecturePinRepository;
use App\Repository\Eloquent\LectureRepository;
use App\Repository\Eloquent\ManagerRepository;
use App\Repository\Eloquent\PaymentItemRepository;
use App\Repository\Eloquent\PaymentRepository;
use App\Repository\Eloquent\PermissionRepository;
use App\Repository\Eloquent\PrintRequestRepository;
use App\Repository\Eloquent\QuestionRepository;
use App\Repository\Eloquent\Repository;
use App\Repository\Eloquent\RoleRepository;
use App\Repository\Eloquent\StandardRepository;
use App\Repository\Eloquent\StructureRepository;
use App\Repository\Eloquent\UserAddressRepository;
use App\Repository\Eloquent\UserRepository;
use App\Repository\Eloquent\NotificationRepository;
use App\Repository\Eloquent\QuestionBankFromQuestionRepository;
use App\Repository\NotificationRepositoryInterface;
use App\Repository\Eloquent\ResetPasswordRepository;
use App\Repository\ResetPasswordRepositoryInterface;
use App\Repository\ExamRepositoryInterface;
use App\Repository\ExamUserRepositoryInterface;
use App\Repository\FieldRepositoryInterface;
use App\Repository\IndicatorRepositoryInterface;
use App\Repository\InfoRepositoryInterface;
use App\Repository\LecturePinRepositoryInterface;
use App\Repository\LectureRepositoryInterface;
use App\Repository\ManagerRepositoryInterface;
use App\Repository\PaymentItemRepositoryInterface;
use App\Repository\PaymentRepositoryInterface;
use App\Repository\PermissionRepositoryInterface;
use App\Repository\PrintRequestRepositoryInterface;
use App\Repository\QuestionBankFromQuestionRepositoryInterface;
use App\Repository\QuestionRepositoryInterface;
use App\Repository\RepositoryInterface;
use App\Repository\RoleRepositoryInterface;
use App\Repository\StandardRepositoryInterface;
use App\Repository\StructureRepositoryInterface;
use App\Repository\UserAddressRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(RepositoryInterface::class, Repository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ResetPasswordRepositoryInterface::class, ResetPasswordRepository::class);
        $this->app->bind(DeviceTokenRepositoryInterface::class, DeviceTokenRepository::class);
        $this->app->bind(UserAddressRepositoryInterface::class, UserAddressRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(ManagerRepositoryInterface::class, ManagerRepository::class);
        $this->app->bind(FieldRepositoryInterface::class, FieldRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(StandardRepositoryInterface::class, StandardRepository::class);
        $this->app->bind(LectureRepositoryInterface::class, LectureRepository::class);
        $this->app->bind(LecturePinRepositoryInterface::class, LecturePinRepository::class);
        $this->app->bind(CourseBookRepositoryInterface::class, CourseBookRepository::class);
        $this->app->bind(CourseAttachmentRepositoryInterface::class, CourseAttachmentRepository::class);
        $this->app->bind(IndicatorRepositoryInterface::class, IndicatorRepository::class);
        $this->app->bind(ExamRepositoryInterface::class, ExamRepository::class);
        $this->app->bind(QuestionRepositoryInterface::class, QuestionRepository::class);
        $this->app->bind(AnswerRepositoryInterface::class, AnswerRepository::class);
        $this->app->bind(ExamUserRepositoryInterface::class, ExamUserRepository::class);
        $this->app->bind(AnswerUserRepositoryInterface::class, AnswerUserRepository::class);
        $this->app->bind(CouponRepositoryInterface::class, CouponRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
        $this->app->bind(CartContentRepositoryInterface::class, CartContentRepository::class);
        $this->app->bind(PrintRequestRepositoryInterface::class, PrintRequestRepository::class);
        $this->app->bind(CourseInquireRepositoryInterface::class, CourseInquireRepository::class);
        $this->app->bind(CourseUserRepositoryInterface::class, CourseUserRepository::class);
        $this->app->bind(BookUserRepositoryInterface::class, BookUserRepository::class);
        $this->app->bind(CourseSubscriptionRepositoryInterface::class , CourseSubscriptionRepository::class);
        $this->app->bind(StructureRepositoryInterface::class , StructureRepository::class);
        $this->app->bind(CourseBookSolutionRepositoryInterface::class , CourseBookSolutionRepository::class);
        $this->app->bind(CertificateUserRepositoryInterface::class , CertificateUserRepository::class);
        $this->app->bind(ContactRepositoryInterface::class , ContactRepository::class);
        $this->app->bind(CourseReviewRepositoryInterface::class , CourseReviewRepository::class);
        $this->app->bind(BookPartRepositoryInterface::class , BookPartRepository::class);
        $this->app->bind(BankRepositoryInterface::class , BankRepository::class);
        $this->app->bind(PaymentItemRepositoryInterface::class , PaymentItemRepository::class);
        $this->app->bind(DevelopmentSettingRepositoryInterface::class , DevelopmentSettingRepository::class);
        $this->app->bind(InfoRepositoryInterface::class , InfoRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class , NotificationRepository::class);
        $this->app->bind(CourseBankQuestionRepositoryInterface::class , CourseBankQuestionRepository::class);
        $this->app->bind(CourseBankQuestionAnswerRepositoryInterface::class , CourseBankQuestionAnswerRepository::class);
        $this->app->bind(QuestionBankFromQuestionRepositoryInterface::class , QuestionBankFromQuestionRepository::class);
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
