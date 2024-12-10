<?php

namespace App\Http\Controllers;

use App\Models\MenuPermission;
use App\Models\Menu;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
class MenuPermissionController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $menus = Menu::with('MenuPermission')->get();
        $menuData = [];
        foreach ($menus as $menu) {
            $menuData[] = [
                'id' => $menu->id,
                'menu' => $menu->name,
                'permissions' => $menu->MenuPermission->pluck('name')->toArray(),
                'permission_ids' => $menu->MenuPermission->pluck('id')->toArray()
            ];
        }

        $permissions = MenuPermission::all();

        return view('menupermission.index', [
            'menus' => $menuData,
            'permissions' => $permissions
        ]);
    }
    
    public function updateMenuPermission(Request $request, $id)
    {
        $menuId = $request->input('menu_id');
        $permissionIds = $request->input('permissions', []);
        
        $menu = Menu::findOrFail($menuId);
        $existingPermissions = $menu->MenuPermission->pluck('id')->toArray();
        $permissionsToAdd = array_diff($permissionIds, $existingPermissions);
        $permissionsToRemove = array_diff($existingPermissions, $permissionIds);       
        if (!empty($permissionsToAdd)) {
            $menu->MenuPermission()->attach($permissionsToAdd);
        }
        if (!empty($permissionsToRemove)) {
            $menu->MenuPermission()->detach($permissionsToRemove);
        }
        
        return redirect()->route('menupermission.index')->with('success', 'Menu permissions updated successfully.');
    }
    
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'permission_name' => 'required|string',
        ]);

        $existingPermission = Permission::where('name', $validatedData['permission_name'])->first();
        if ($existingPermission) {
            return redirect()->route('menupermission.list')->with('error', 'A menu permission with that name already exists.');
        }

        $permission = Permission::create([
            'name' => $validatedData['permission_name'],
            'guard_name' => 'web',
            'created_at' => now(),
        ]);

        return redirect()->route('menupermission.index')->with('success', 'Menu permission added successfully.');
    }
    
    public function update(Request $request, $id)
    {
               
            $validatedData = $request->validate([
                'permission_name' => 'required|string'
            ]);
            $permission = MenuPermission::findOrFail($id);
            
            $permission->update([
                'name' => $validatedData['permission_name']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permission updated successfully.'
        ]);
    }

    public function destroy($id)
    {
        try {
            $permission = MenuPermission::findOrFail($id);
            $permission->menus()->detach();
            $permission->delete();
            return response()->json(['success' => 'Permission successfully deleted.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the permission.'], 500);
        }
    }
}
