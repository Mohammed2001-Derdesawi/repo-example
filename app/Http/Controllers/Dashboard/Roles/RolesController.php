<?php

namespace App\Http\Controllers\Dashboard\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Roles\RolesRequest;
use App\Http\Services\Dashboard\Roles\RoleService;
use App\Models\Permission;
use App\Models\Role;
use App\Repository\RoleRepositoryInterface;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    private RoleRepositoryInterface $roleRepository;
    private RoleService $role;

    public function __construct(RoleRepositoryInterface $roleRepository , RoleService $roleService){
        $this->roleRepository = $roleRepository;
        $this->role = $roleService;
    }
    public function index(){
        $roles = $this->roleRepository->getAll();
        return view('dashboard.site.roles.index' , ['roles' => $roles]);
    }

    public function create(){
        $permissions = $this->role->buildPermissionForm();
        return view('dashboard.site.roles.create' , ['permissions' => $permissions]);
    }
    public function store(RolesRequest $request){
       return $this->role->store($request);
    }

    public function edit($id){
        $role = $this->roleRepository->getById($id);
        $permissions = $this->role->buildPermissionForm();
        return view('dashboard.site.roles.edit' , [
            'role' => $role,
            'permissions' => $permissions
        ]);
    }

    public function update(RolesRequest $request , $id){
        return $this->role->update($request , $id);
    }

    public function destroy($id){
         return $this->role->deleteRole($id);
    }
}
