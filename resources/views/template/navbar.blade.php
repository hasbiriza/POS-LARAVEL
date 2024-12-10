<nav class="navbar navbar-expand-lg navbar-light bg-primary" style="flex-shrink: 0; padding-top: 0.2rem; padding-bottom: 0.2rem;">
  <div class="container-fluid">
    <!-- Layout Menu Toggle (always visible) -->
    <div class="layout-menu-toggle navbar-nav align-items-center me-2 me-xl-0 d-flex d-xl-none">
      <a class="nav-item nav-link px-0" href="javascript:void(0)">
        <i class="bx bx-menu bx-sm"></i>
      </a>
    </div>

    <!-- Button Group -->
    <div class="navbar-nav align-items-center flex-grow-1 d-flex">
      <div class="nav-item d-flex align-items-center w-100">
        <div class="btn-group btn-group-sm" role="group" aria-label="Action Buttons">
          <!-- Button Jual -->
          <a href="{{ route('sales-transaction.index') }}" class="btn btn-warning custom-btn-color">
            <i class="bx bx-cart-alt"></i> 
          </a>
          <!-- Button Beli -->
          <a href="{{ route('purchase.add') }}" class="btn btn-success">
            <i class="bx bx-dollar-circle"></i> 
          </a>          
        </div>
      </div>
    </div>

       <!-- User Profile Dropdown -->
    <ul class="navbar-nav flex-row align-items-center ms-auto position-relative">
      <!-- Notification Icon -->
      <!-- <li class="nav-item dropdown-notification dropdown position-relative">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <i class="bx bx-bell bx-sm text-white"></i>
          <span class="badge bg-danger rounded-pill badge-notification">3</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end position-absolute top-100 end-0 mt-2" style="z-index: 1000; min-width: 200px;">
          <li>
            <a class="dropdown-item" href="#">
              <i class="bx bx-user me-2"></i>
              <span>New user registered</span>
              <small class="text-muted d-block">1 hour ago</small>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <i class="bx bx-message me-2"></i>
              <span>New comment on your post</span>
              <small class="text-muted d-block">3 hours ago</small>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <i class="bx bx-log-out me-2"></i>
              <span>System update available</span>
              <small class="text-muted d-block">1 day ago</small>
            </a>
          </li>
        </ul>
      </li> -->
      <!-- User Profile Dropdown -->
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar avatar-online">
            <img src="{{ asset('assets/img/user.png') }}" alt="Avatar" class="w-px-40 h-auto rounded-circle" />
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end position-absolute top-100 end-0 mt-2" style="z-index: 1000; min-width: 200px;">
          <li>
            <a class="dropdown-item" href="#">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar avatar-online">
                    <img src="{{ asset('assets/img/user.png') }}" alt="Avatar" class="w-px-40 h-auto rounded-circle" />
                  </div>
                </div>
                <div class="flex-grow-1">
                  <span class="fw-medium d-block">{{ Auth::user()->name }}</span>
                  <small class="text-muted">
                    @if(count(Auth::user()->roles) > 1)
                    {{ Auth::user()->roles->pluck('name')->implode(', ') }}
                    @else
                    {{ Auth::user()->roles->first()->name ?? 'No Role' }}
                    @endif
                  </small>
                </div>
              </div>
            </a>
          </li>
          <li>
            <div class="dropdown-divider"></div>
          </li>
          <li>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                                    this.closest('form').submit();">
                <i class="bx bx-power-off me-2"></i>
                <span class="align-middle">Log Out</span>
              </a>
            </form>
          </li>
        </ul>
      </li>
      <!--/ User Profile Dropdown -->
    </ul>
  </div>
</nav>

<header class="bg-white shadow">
  <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8" style="height: 55px; display: flex; align-items: center;">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb m-0">
        <li class="breadcrumb-item">
          <a href="{{ route('dashboard') }}">Beranda</a>
        </li>
        @yield('breadcrumb')
      </ol>
    </nav>
  </div>
</header>