<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        $layout = Setting::getLayout();
        return view('settings.index', compact('setting', 'layout'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'layout' => 'required|exists:layouts,id',
        ]);

        $setting = Setting::first();
        if ($request->hasFile('logo')) {
            $imageName = time() . '.' . $request->logo->extension();
            $request->logo->move(public_path('images'), $imageName);
            $setting->logo = $imageName;
        }

        $setting->name = $request->name;
        $setting->save();
        $this->saveLayoutPreference($request);

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }

    public function saveLayoutPreference(Request $request) {
        $user       = auth()->user();
        $stores     = $user->getstores;
        $storeId    = $stores->first()->id;
        $layoutId   = $request->input('layout');
        Setting::saveLayoutPreference($storeId, $layoutId);

        $storeLayout = DB::table('store_layout_preferences')
            ->where('store_id', $storeId)
            ->first();
        $layoutSettings = DB::table('layout_settings')
            ->where('layout_id', $layoutId)
            ->first();

        session([
            'sidebar_color' => $storeLayout->sidebar_color ?? $layoutSettings->sidebar_color,
            'app_brand_color' => $storeLayout->app_brand_color ?? $layoutSettings->app_brand_color,
            'menu_link_color' => $storeLayout->menu_link_color ?? $layoutSettings->menu_link_color,
            'menu_link_hover_color' => $storeLayout->menu_link_hover_color ?? $layoutSettings->menu_link_hover_color,
            'navbar_color' => $storeLayout->navbar_color ?? $layoutSettings->navbar_color,
            'button_color' => $storeLayout->button_color ?? $layoutSettings->button_color,
            'button_hover_color' => $storeLayout->button_hover_color ?? $layoutSettings->button_hover_color,
            'fonts' => $storeLayout->fonts ?? $layoutSettings->fonts,
            'layout_id' => $layoutId,
        ]);
    }
    
}
