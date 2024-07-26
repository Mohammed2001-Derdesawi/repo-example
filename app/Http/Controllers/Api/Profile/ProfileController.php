<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\ProfileDetailsRequest;
use App\Http\Requests\Api\Profile\ProfilePasswordRequest;
use App\Http\Services\Api\Certificate\CertificateService;
use App\Http\Services\Api\Profile\ProfileService;
use App\Http\Services\Mutual\GetService;
use App\Repository\CourseBookRepositoryInterface;

class ProfileController extends Controller
{

    protected ProfileService $profile;
    protected CourseBookRepositoryInterface $courseBookRepository;
    protected GetService $get;
    protected CertificateService $certificates;

    public function __construct(
        ProfileService $profileService,
        GetService $getService,
        CertificateService $certificateService,
        CourseBookRepositoryInterface $courseBookRepository,
    )
    {
        $this->middleware('auth:api');
        $this->profile = $profileService;
        $this->get = $getService;
        $this->certificates = $certificateService;
        $this->courseBookRepository = $courseBookRepository;
    }

    public function details() {
        return $this->profile->details();
    }

    public function updateDetails(ProfileDetailsRequest $request) {
        return $this->profile->update($request);
    }

    public function updatePassword(ProfilePasswordRequest $request) {
        return $this->profile->update($request);
    }

    public function progressCourses(){
        return $this->profile->progressCourses();

    }
    public function finishedCourses(){
        return $this->profile->finishedCourses();
    }

    public function myCourses(){
        return $this->profile->myCourses();
    }

    public function requestedCertificates() {
        return $this->certificates->getRequested();
    }

    public function requestableCertificates() {
        return $this->certificates->getRequestable();
    }

    public function books(){
        return $this->profile->books();
    }

    public function payments() {
        return $this->profile->payments();
    }
}
