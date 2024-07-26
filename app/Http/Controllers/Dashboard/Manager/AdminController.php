<?php

namespace App\Http\Controllers\Dashboard\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Managers\ManagerRequest;
use App\Http\Services\Dashboard\Managers\ManagerService;
use App\Repository\ManagerRepositoryInterface;
use Illuminate\Http\Request;

class AdminController extends ManagerController
{
    protected string $role = 'admin';
//    private ManagerRepositoryInterface $managerRepository;
//    protected ManagerService $manager;
//
//    public function __construct(
//        ManagerRepositoryInterface $managerRepository,
//        ManagerService $manager,
//    )
//    {
//        $this->middleware('auth');
//        $this->managerRepository = $managerRepository;
//        $this->manager = $manager;
//    }
//
//    public function index()
//    {
//        $admins = $this->managerRepository->paginate();
//        return view('dashboard.site.managers.admins.index', compact('admins'));
//    }
//
//    public function create()
//    {
//        return view('dashboard.site.managers.admins.create');
//    }
//
//    public function store(ManagerRequest $request)
//    {
//        return $this->manager->store($request, 'admin');
//    }
//
//    public function show($id)
//    {
//        $student = $this->managerRepository->getById($id);
//        return view('dashboard.site.student.show', compact('student'));
//    }
//
//    public function edit($id)
//    {
//        $student = $this->managerRepository->getById($id);
//        return view('dashboard.site.student.edit', compact('student'));
//    }
//
//    public function update(ManagerRequest $request, $id)
//    {
//        return $this->manager->update($request, $id);
//    }
//
//    public function destroy($id)
//    {
////        return $this->manager->delete($id);
//    }
}
