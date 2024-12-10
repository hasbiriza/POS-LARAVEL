<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        //custom session 
        $user = Auth::user();
        if (!$user->hasRole('superadmin')) {
            $stores     = $user->getstores;
            $storeId    = $stores->first()->id;
            $storeLayout = DB::table('store_layout_preferences')
                ->where('store_id', $storeId)
                ->first(); 
            $layoutSettings = DB::table('layout_settings')->where('layout_id', $storeLayout ? $storeLayout->layout_id : 1)->first();        
            Session::put('sidebar_color', $storeLayout->sidebar_color ?? $layoutSettings->sidebar_color);
            Session::put('app_brand_color', $storeLayout->app_brand_color ?? $layoutSettings->app_brand_color);
            Session::put('menu_link_color', $storeLayout->menu_link_color ?? $layoutSettings->menu_link_color);
            Session::put('menu_link_hover_color', $storeLayout->menu_link_hover_color ?? $layoutSettings->menu_link_hover_color);
            Session::put('navbar_color', $storeLayout->navbar_color ?? $layoutSettings->navbar_color);
            Session::put('button_color', $storeLayout->button_color ?? $layoutSettings->button_color);
            Session::put('button_hover_color', $storeLayout->button_hover_color ?? $layoutSettings->button_hover_color);
            Session::put('fonts', $storeLayout->fonts ?? $layoutSettings->fonts);
            Session::put('layout_id', $storeLayout->layout_id ?? $layoutSettings->layout_id);
        }


        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
