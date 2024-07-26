<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\Course\CourseTeacherResource;
use App\Http\Services\Api\Manager\ManagerService;
use App\Http\Services\Mutual\GetService;
use App\Repository\ManagerRepositoryInterface;

class ManagerController extends Controller
{

    private ManagerService $managerService;


    public function __construct(ManagerService $managerService){
        $this->managerService = $managerService;
    }

    public function show($id){
        return $this->managerService->show($id);
    }
}
