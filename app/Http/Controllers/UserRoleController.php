<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserRoleController extends Controller
{
    public function index()
    {
        $userRoles = Role::all();
        $permissions = Permission::orderBy('name', 'asc')->get();
        return view('userroles.index', [
            'userRoles' => $userRoles,
            'permissions' => $permissions
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|max:255',
            'permissions' => 'nullable|array'
        ]);

        $role = Role::create(['name' => $request->role_name]);

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('userroles.index')->with('success', 'Role and permissions created successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'role_name' => 'required|string|max:255',
            'permissions' => 'nullable|array'
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->role_name;
        $role->save();

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('userroles.index')->with('success', 'Role and permissions updated successfully');
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();
            return response()->json(['success' => 'Role deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the role.'], 500);
        }
    }

    public function getPermissions($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return response()->json([
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions
        ]);
    }
}
