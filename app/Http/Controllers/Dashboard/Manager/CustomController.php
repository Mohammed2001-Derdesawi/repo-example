<?php

namespace App\Http\Controllers\Dashboard\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Managers\ManagerRequest;
use App\Http\Services\Dashboard\Managers\ManagerService;
use App\Http\Services\Mutual\ExportService;
use App\Repository\ManagerRepositoryInterface;
use App\Repository\RoleRepositoryInterface;
use Illuminate\Http\Request;

class CustomController extends ManagerController
{
    public function __construct(ManagerRepositoryInterface $managerRepository, RoleRepositoryInterface $roleRepository, ManagerService $manager, ExportService $export)
    {
        parent::__construct($managerRepository, $roleRepository, $manager, $export);
        $this->role = \request('role');
        $this->middleware('permission:customs-read')->only('index' , 'show');
        $this->middleware('permission:customs-create')->only('create', 'store');
        $this->middleware('permission:customs-delete')->only('destroy');
    }

    public function _show($role, $id)
    {
        return parent::show($id);
    }

    public function _edit($role, $id)
    {
        return parent::edit($id);
    }

    public function _update(ManagerRequest $request, $role, $id)
    {
        return parent::update($request, $id);
    }

    public function _destroy($role, $id)
    {
        return parent::destroy($id);
    }

    public function _export($role, $id)
    {
        return parent::export($id);
    }

}
