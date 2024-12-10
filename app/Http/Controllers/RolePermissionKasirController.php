<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Stores;

class RolePermissionKasirController extends Controller
{
    public function index()
    {
        $userRoles = Role::where('name', '!=', 'superadmin')->get();
        $permissions = Permission::orderBy('name', 'asc')->get();
        $kasirUsers = User::role($userRoles)->with('getstores')->get();
        $stores = Stores::all();
        $kasirUsersWithStores = $kasirUsers->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'stores' => $user->getstores ? $user->getstores->pluck('store_name', 'id')->toArray() : []
            ];
        });
        
        return view('rolepermissionkasir.index', [
            'userRoles' => $userRoles,
            'permissions' => $permissions,
            'kasirUsers' => $kasirUsersWithStores,
            'stores' => $stores
        ]);
    }

    public function edit($id)
    {
        $user = User::find($id);
        $userStores = $user->getstores->pluck('id')->toArray();
        return response()->json(['userStores' => $userStores]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->syncRoles('kasir');
        $user->getstores()->sync($request->stores);
        return response()->json(['message' => 'Kasir berhasil diupdate']);
    }
}
