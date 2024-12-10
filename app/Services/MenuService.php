<?php 
namespace App\Services;

use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class MenuService
{
    public function getMenu()
    {
        $user = Auth::user();
        $roles = $user->roles;
        $permissions = $roles->flatMap(function ($role) {
            return $role->permissions;
        })->pluck('name');

        // Dapatkan ID menu yang pengguna memiliki izin untuk
        $menuIds = Menu::whereIn('id', function ($query) use ($permissions) {
            $query->select('menu_id')
                  ->from('menu_permissions')
                  ->whereIn('permission_id', function ($query) use ($permissions) {
                      $query->select('id')
                            ->from('permissions')
                            ->whereIn('name', $permissions);
                  });
        })->pluck('id');

        // Ambil menu utama dan anak-anaknya berdasarkan izin
        $menus = Menu::with(['children' => function ($query) use ($menuIds) {
            $query->whereIn('id', $menuIds)->ordered();
        }])
        ->whereIn('id', $menuIds)
        ->whereNull('parent_id')
        ->ordered()
        ->get();

        return $menus;
    }
}
