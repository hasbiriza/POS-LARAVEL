<?php

namespace App\Http\Controllers;

use App\Models\MenuPermission;
use App\Models\Menu;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MenuRoleController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    { 
        $roles = Role::with('permissions')->get();        
        $rolesWithPermissions = $roles->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('name')->toArray()
            ];
        });
        
        $allPermissions = Permission::all();
        
        return view('menurole.index', [
            'roles' => $rolesWithPermissions,
            'allPermissions' => $allPermissions
        ]);
    }
    
    public function edit($id)
    {
        $role = Role::find($id);
        return view('menurole.edit', [
            'role' => $role
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);
        
        return redirect()->route('menurole.index')->with('success', 'Role Permissions updated successfully.');
    }

}
