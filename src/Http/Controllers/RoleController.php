<?php

namespace Budhaspec\Rolepermission\Http\Controllers;

use Budhaspec\Rolepermission\Models\Role;
use Illuminate\Http\Request;
use Budhaspec\Rolepermission\Models\Module;
use Budhaspec\Rolepermission\Models\Permission;
use Illuminate\Routing\Controller as BaseController;

class RoleController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('access::roles.index', compact('roles'));
    }

    public function updatePermissions(Request $request, Role $role)
    {
        $role->permissions()->delete();
        foreach ($request->input('permissions', []) as $key => $moduleId) {
            $permissions = [];
            $parentModule = Module::where('id', $key)->whereNull('parent_id')->pluck('name')->toArray();
            $subModule = Module::where('parent_id', $key)->whereIn('id', $moduleId)->pluck('name', 'id')->toArray();
            if (!empty($parentModule)) {                
                $permissions[] = new Permission([
                    'role_id' => $role->id,
                    'module_id' => $key,
                    'slug' => !empty($parentModule) ? $parentModule[0] : null
                ]);
            }
            if (!empty($subModule)) {
                foreach ($moduleId as $k => $menuId) {
                    if(array_key_exists($menuId, $subModule)) {
                        $permissions[] = new Permission([
                            'role_id' => $role->id,
                            'module_id' => $menuId,
                            'slug' => (!empty($parentModule) ? $parentModule[0] : null).'.'.$subModule[$menuId]
                        ]);
                    }
                }
            }
            if (!empty($permissions)) {
                $role->permissions()->saveMany($permissions);
            }
        }
        return redirectWithAlert('Permissions updated.', 'success', 'roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|unique:roles,name'
        ], [
            'name.unique' => 'This Role has already been taken.'
        ]);
        Role::create($validator);
        return redirectWithAlert('Role has been added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
     $modules = Module::whereNull('parent_id')->get();
     return view('access::roles.permissions', compact('role', 'modules'));
 }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirectWithAlert('Role has been deleted!');
    }
}
