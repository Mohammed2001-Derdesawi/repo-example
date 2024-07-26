<?php

namespace App\Http\Services\Dashboard\Roles;

use App\Http\Requests\Dashboard\Roles\RolesRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Repository\PermissionRepositoryInterface;
use App\Repository\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class RoleService
{

    private RoleRepositoryInterface $roleRepository;
    private PermissionRepositoryInterface $permissionRepository;
    public function __construct(RoleRepositoryInterface $roleRepository, PermissionRepositoryInterface $permissionRepository){
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function buildPermissionForm(): array{
        $permissions = $this->permissionRepository->permissionForm();
        $build = [];
        foreach($permissions as $permission){
            $permission = explode('-', $permission, 2);
            $build[$permission[0]][] = $permission[1];
        }
        return $build;
    }
    public function store(RolesRequest $request){
        try {
            DB::beginTransaction();
            $role = $this->storeRole($request->validated());
            if ($request->has('permissions'))
                $this->attachPermissionsToRole($role , $request['permissions']);
            DB::commit();
            return redirect()->route('roles.index')->with(['success' => __('messages.Role created successfully')]);

        }catch (\Exception $e){
            DB::rollBack();
            return redirect()->route('roles.create')->with(['error' => __('messages.Something went wrong')]);

        }
    }
    private function storeRole($data){
        $data['name'] = strtolower(str_replace(' ' , '-' , $data['display_name_en']));
        return $this->roleRepository->create($data);
    }

    public function update(RolesRequest $request , $id){
        try {
            DB::beginTransaction();
            $this->updateRole($request->validated() , $id);
            $role = $this->roleRepository->getById($id, ['id']);
            $this->syncPermissions($role , $request['permissions']);
            DB::commit();
            return redirect()->route('roles.index')->with(['success' => __('messages.Role updated successfully')]);
        }catch (\Exception $e){
            DB::rollBack();
            return redirect()->route('roles.create')->with(['error' => __('messages.Something went wrong')]);
        }
    }

    private function updateRole($data , $id){
        $this->roleRepository->update($id , $data);
    }

    private function attachPermissionsToRole($role , $data){
        $role->attachPermissions($data);
    }

    private function syncPermissions($role , $data){
        $role->syncPermissions($data);
    }

    public function deleteRole($id){
        $role = $this->roleRepository->getById($id);
        if (Gate::allows('delete-role', $role)) {
            $role->delete();
            return redirect()->route('roles.index')->with(['success' => __('messages.Role deleted successfully')]);
        } else {
            return redirect()->route('roles.index')->with(['error' => __('messages.Role cannotBeDeleted')]);
        }
    }
}
