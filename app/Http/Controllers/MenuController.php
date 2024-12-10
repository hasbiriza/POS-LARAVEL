<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $menus = Menu::all();
        return view('menus.index', compact('menus'));
    }
    
    public function create()
    {
        $menus = Menu::whereNull('parent_id')->get();
        return view('menus.create', compact('menus'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:menus,id'],
        ]);

        $sortOrder = 0;
        if (!empty($request->parent_id)) {
            $sortOrder = 99;
        } else {
            $sortOrder = Menu::whereNull('parent_id')->max('sort_order') + 1;
        }

        $menu = Menu::create([
            'name' => $request->name,
            'url' => $request->url ?: "",
            'icon' => $request->icon,
            'parent_id' => $request->parent_id,
            'sort_order' => $sortOrder,
        ]);

        $type = $menu ? 'success' : 'error';
        $message = $menu ? 'Menu added successfully.' : 'Failed to add menu. Please try again.';
        return redirect()->route('menus.index')->with($type, $message);
    }

    public function edit($id): View
    {
        $menus = Menu::whereNull('parent_id')->get();
        $menu = Menu::findOrFail($id);
        return view('menus.edit', [
            'menu' => $menu,
            'menus' => $menus
        ]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $menu = Menu::findOrFail($id);
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:menus,id'],
        ]);
        
        $updateData = [
            'name' => $request->name,
            'url' => $request->url ?: "",
            'icon' => $request->icon,
            'parent_id' => $request->parent_id,
        ];
        
        if (empty($request->parent_id)) {
            $maxSortOrder = Menu::whereNull('parent_id')->max('sort_order');
            $updateData['sort_order'] = $maxSortOrder + 1;
        } else {
            $updateData['sort_order'] = 99;
        }
        
        $menu->update($updateData);
        
        return redirect()->route('menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $menu = Menu::findOrFail($id);
            $menu->delete();
            return response()->json(['success' => 'Menu successfully deleted.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the menu.'], 500);
        }
    }
}
