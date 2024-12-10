<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="#" class="app-brand-link">
              <span class="app-brand-logo demo">
                     @if (\App\Helpers\SettingsHelper::getSetting('logo'))
                        <img src="{{ asset('images/' . \App\Helpers\SettingsHelper::getSetting('logo')) }}" alt="Logo" width="50" height="50">
                    @endif
              </span>
              <span class="app-brand-text demo menu-text fw-bold ms-2" style="text-transform: capitalize !important;">{{ \App\Helpers\SettingsHelper::getSetting('name') ?? config('app.name', 'LARAVEL') }}</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>

 <div class="menu-inner-shadow"></div>

@auth
<ul class="menu-inner py-1">
<li class="menu-item">
        <span class="menu-link">Sidebar loaded: {{ now() }}</span>
    </li>
    @inject('menuService', 'App\Services\MenuService')
    @php
        $menus = $menuService->getMenu();
    @endphp
    @foreach($menus as $menu)
        @php
            $is_active = request()->is($menu->url) || request()->is($menu->url.'/*');
            $has_submenus = $menu->children->count() > 0;
            $has_active_submenu = $has_submenus && $menu->children->contains(function($submenu) {
                return request()->is($submenu->url) || request()->is($submenu->url.'/*');
            });
        @endphp
        <li class="menu-item {{ $is_active || $has_active_submenu ? 'active open' : '' }}">
            <a href="{{ url($menu->url) }}" class="menu-link {{ $has_submenus ? 'menu-toggle' : '' }}">
                <i class="menu-icon tf-icons bx {{ $menu->icon }}"></i>
                <div data-i18n="{{ $menu->name }}">{{ $menu->name }}</div>
            </a>
            @if($has_submenus)
                <ul class="menu-sub">
                    @foreach($menu->children as $submenu)
                        @php
                            $is_submenu_active = request()->is($submenu->url) || request()->is($submenu->url.'/*');
                        @endphp
                        <li class="menu-item {{ $is_submenu_active ? 'active' : '' }}">
                            <a href="{{ url($submenu->url) }}" class="menu-link">{{ $submenu->name }}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>
@endauth
     
</aside>