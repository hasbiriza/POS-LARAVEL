<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Auth</title>
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />

  <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/form-validation.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">

  <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>
  <script src="{{ asset('assets/js/config.js') }}"></script>

</head>

<body style="background: linear-gradient(to bottom right, rgba(0,0,255,0.5), rgba(128,0,128,0.5));">

  <!-- Content -->

  <div class="auth-layout">
    <div class="auth-image"></div>
    <!-- Right: Register Form -->
    <div class="auth-form">
      <div class="form-container">
        <!-- Logo -->
        <div class="app-brand justify-content-center">
          <a href="#" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">
              @if (\App\Helpers\SettingsHelper::getSetting('logo'))
              <img src="{{ asset('images/' . \App\Helpers\SettingsHelper::getSetting('logo')) }}" alt="Logo" width="250">
              @endif
            </span>
            <!-- <span class="app-brand-text demo menu-text fw-bold ms-2" style="text-transform: capitalize !important;">{{ ucwords(strtolower(\App\Helpers\SettingsHelper::getSetting('name') ?? config('app.name', 'Laravel Kit'))) }}</span> -->

          </a>
        </div>
        <!-- /Logo -->
        <!-- <h4 class="mb-1">Welcome</h4>
        <p class="mb-6">Please sign-in to your account</p> -->
        <form class="mb-3 fv-plugins-bootstrap5 fv-plugins-framework" method="POST" action="{{ route('login') }}">
          @csrf
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required autofocus autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
          </div>
          <div class="mb-3 form-password-toggle">
            <label class="form-label" for="password">Password</label>
            <div class="input-group input-group-merge">
              <input type="password" id="password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" name="password" required autocomplete="current-password">
              <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
          </div>
          <div class="mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
              <label class="form-check-label" for="remember_me">
                {{ __('Remember me') }}
              </label>
            </div>
          </div>
          <div class="mb-3">
            <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
          </div>

          <div class="divider my-4">
            <div class="divider-text">or</div>
          </div>
          <p class="text-center">
            <span>New on our platform?</span>
            <a href="{{route('register')}}">
              <span>Create an account</span>
            </a>
          </p>
          @if (Route::has('password.request'))
          <p class="text-center">
            <a href="{{ route('password.request') }}">
              {{ __('Forgot your password?') }}
            </a>
          </p>
          @endif
          <div class="d-flex justify-content-center">
            <a href="javascript:;" class="btn btn-sm btn-icon rounded-circle btn-text-facebook me-1_5">
              <i class='bx bxl-facebook-circle'></i>
            </a>

            <a href="javascript:;" class="btn btn-sm btn-icon rounded-circle btn-text-twitter me-1_5">
              <i class="tf-icons bx bxl-twitter"></i>
            </a>

            <a href="javascript:;" class="btn btn-sm btn-icon rounded-circle btn-text-github me-1_5">
              <i class='bx bxl-github'></i>
            </a>

            <a href="javascript:;" class="btn btn-sm btn-icon rounded-circle btn-text-google-plus">
              <i class="tf-icons bx bxl-google"></i>
            </a>
          </div>
        </form>
      </div>
    </div>
    <!-- /Register -->
  </div>


  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/@form-validation/popular.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/@form-validation/auto-focus.js') }}"></script>

  <!-- Main JS -->
  <script src="{{ asset('assets/js/main.js') }}"></script>


  <!-- Page JS -->
  <!-- <script src="{{ asset('assets/js/pages-auth.js') }}"></script> -->

</body>

</html>

<!-- beautify ignore:end -->