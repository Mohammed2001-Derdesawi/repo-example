<?php

namespace App\Http\Controllers\Dashboard\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeacherController extends ManagerController
{
    protected string $role = 'teacher';
}
