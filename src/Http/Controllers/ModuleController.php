<?php

namespace Budhaspec\Rolepermission\Http\Controllers;

use Budhaspec\Rolepermission\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Budhaspec\Rolepermission\Models\Role;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Budhaspec\Rolepermission\Models\Permission;

class ModuleController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parentModules = Module::whereNull('parent_id')->get();
        return view('access::modules.index', compact('parentModules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function childUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => [
                'required',
                Rule::unique('modules')
                ->where(function ($query) use ($request) {
                    return $query->where('id', '<>', $request->module_id)
                    ->where('parent_id', $request->parent_id);
                }),
            ],
            'parent_id' => 'required|exists:modules,id',
            'module_id' => 'required|exists:modules,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'=>false,
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        }
        $module = Module::withWhereHas('children', function($query) use ($request) {
            $query->where('id', $request->module_id);
        })->find($request->parent_id);
        if (!blank($module)) {
            $module->children->first()->name = $request->name;
            $module->children->first()->save();
            $parentName = $module->name;
            $childName = $request->name;
            $permissionSlug = "{$parentName}.{$childName}";
            Permission::where('module_id', $request->module_id)
            ->update(['slug'=>$permissionSlug]);
        }
        return response()->json([
            'status' => true,
            'data' => $request->name ?? ''
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|unique:modules,name',
            'parent_id' => 'nullable'
        ]);
        Module::create($validator);
        return redirectWithAlert('Module has been added!');
    }

    public function deleteChild(Request $request)
    {
        $request->validate([
            'moduleId' => 'required|exists:modules,id'
        ]);
        
        $module = Module::find($request->moduleId);
        $module->delete();
        return redirectWithAlert('Module has been deleted.');
    }

    public function listUsers(Request $request) 
    {
        $users = User::all();
        return view('access::modules.list-users', compact('users'));
    }

    public function editUser(Request $request, User $user)
    {  
        $roles = Role::all();
        return view('access::modules.edit-user', compact('user', 'roles'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validator = $request->validate([
            'name' => 'required|min:1|max:80',
            'email' => 'required|email',
            'role_id' => 'required|exists:roles,id'
        ]);
        $user->fill($validator);
        if (!$user->save()) {
            return back()->withErrors($validator)->withInput();
        }
        return redirectWithAlert('User has been updated!', 'success', 'user.list');
    }
}
