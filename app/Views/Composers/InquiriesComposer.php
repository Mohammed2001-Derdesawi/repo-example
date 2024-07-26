<?php

namespace App\Views\Composers;

use App\Repository\CourseInquireRepositoryInterface;
use Illuminate\View\View;

class InquiriesComposer {

    private CourseInquireRepositoryInterface $courseInquireRepository;

    public function __construct(
        CourseInquireRepositoryInterface $courseInquireRepository,
    ) {
        $this->courseInquireRepository = $courseInquireRepository;
    }

    public function compose(View $view) {
        $inquiriesCount = $this->courseInquireRepository->inquiriesCount();
        $view->with('inquiriesCount', $inquiriesCount);
   }
}
