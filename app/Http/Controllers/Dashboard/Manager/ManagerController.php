<?php

namespace App\Http\Controllers\Dashboard\Manager;

use App\Http\Contracts\Exportable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Managers\ManagerRequest;
use App\Http\Services\Dashboard\Managers\ManagerService;
use App\Http\Services\Mutual\ExportService;
use App\Repository\ManagerRepositoryInterface;
use App\Repository\RoleRepositoryInterface;
use Illuminate\Http\Request;

abstract class ManagerController extends Controller implements Exportable
{
    private ManagerRepositoryInterface $managerRepository;
    private RoleRepositoryInterface $roleRepository;
    private ManagerService $manager;

    private ExportService $export;

    protected string $role;

    private $currentRole;

    public function __construct(
        ManagerRepositoryInterface $managerRepository,
        RoleRepositoryInterface $roleRepository,
        ManagerService $manager,
        ExportService $exportService,
    )
    {
        $this->managerRepository = $managerRepository;
        $this->roleRepository = $roleRepository;
        $this->manager = $manager;
        $this->export = $exportService;
        $this->currentRole = $roleRepository->getByName(request('role') ?? '');
    }

    public function index()
    {
        $managers = $this->managerRepository->getManagers($this->role)->paginate(10);
        return view()->exists('dashboard.site.managers.' . $this->role . 's.index')
            ? view('dashboard.site.managers.' . $this->role . 's.index', ['managers' => $managers])
            : view('dashboard.site.managers.customs.index', ['managers' => $managers, 'currentRole' => $this->currentRole]);
    }

    public function create()
    {
        return view()->exists('dashboard.site.managers.' . $this->role . 's.create')
            ? view('dashboard.site.managers.' . $this->role . 's.create')
            : view('dashboard.site.managers.customs.create', ['currentRole' => $this->currentRole]);
    }

    public function store(ManagerRequest $request)
    {
//        dd($this->role);
        return $this->manager->store($request, $this->role);
    }

    public function show($id)
    {
        $manager = $this->managerRepository->getById($id);
        return view()->exists('dashboard.site.managers.' . $this->role . 's.show')
            ? view('dashboard.site.managers.' . $this->role . 's.show', ['manager' => $manager])
            : view('dashboard.site.managers.customs.show', ['manager' => $manager, 'currentRole' => $this->currentRole]);
    }

    public function edit($id)
    {
        $manager = $this->managerRepository->getById($id);
        return view()->exists('dashboard.site.managers.' . $this->role . 's.edit')
            ? view('dashboard.site.managers.' . $this->role . 's.edit', ['manager' => $manager])
            : view('dashboard.site.managers.customs.edit', ['manager' => $manager, 'currentRole' => $this->currentRole]);
    }

    public function update(ManagerRequest $request, $id)
    {
        return $this->manager->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->manager->delete($id);
    }

    public function export(string $type)
    {
        $managers = $this->managerRepository->getManagers($this->role)->get();

        $data = [
            'managers' => $managers,
        ];

        return $this->export->handle('managers', 'dashboard.site.managers.export', $data, $this->role.'s', $type);
    }
}
