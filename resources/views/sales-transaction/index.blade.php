<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  @vite('resources/css/app.css')
  <!-- @vite('resources/js/app.js') -->

  <title>@yield('title')</title>

  <meta name="description" content="" />

  <!-- manifest -->
  <link rel="manifest" href="{{ asset('manifest.json') }}">

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/cepatonline.ico') }}" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
  <!-- Icons -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="{{ asset('assets/css/sales-transaction.css') }}" />
  <!-- <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" /> -->

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
  
  <!-- Library Ekspor data Table Button -->
  <!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css"> -->

  @yield('css')

  <!-- Page CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">

  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

  <!-- Helpers -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>
  <script src="{{ asset('assets/js/config.js') }}"></script>

  <!-- Data Table -->
  <!-- <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/js/ui-toasts.js') }}"></script> -->


<audio id="beepSound" src="{{ asset('audio/beep.wav') }}" preload="auto"></audio>

<style>

/*----------------  Navbar ----------------*/
.navbar.bg-white {
    background-color: {{ Session::get('navbar_color') }} !important;
  }

  .nav-link {
    color: {{ Session::get('menu_link_color') }} !important;
  }
    
 .nav-link:hover {
    color: {{ Session::get('menu_link_hover_color') }} !important; 
  }

</style>


</head>

<body class="h-full">
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Layout container -->
      <div class="layout-page">
      @include('sales-transaction.header')
      <!-- Content wrapper -->
        <div class="content-wrapper">
          <div class="content-body">
            <!-- <div class="mx-auto max-w-full px-4 py-6 sm:px-6 lg:px-8"> -->
            <div class="mx-auto max-w-full px-3 py-3 sm:px-4 lg:px-6">

            <div class="row">
              <div class="col-md-6">
                @include('sales-transaction.cart')
              </div>
              <div class="col-md-6 product">
                @include('sales-transaction.product')
              </div>
            </div>

              @yield('content')
            </div>
          </div>

       


        </div>
        <!-- / Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>
    <!-- / Layout container -->

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>
  <!-- / Layout wrapper -->

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

  <!-- endbuild -->

  <!-- Main JS -->
  <script src="{{ asset('assets/js/main.js') }}"></script>

  <script>
    $(document).ready(function() {
   

   /* Disable scroll on the body */
   function toggleBodyScroll(enable) {
     if (enable) {
       $('body').removeClass('no-scroll');
     } else {
       $('body').addClass('no-scroll');
     }
   }
   toggleBodyScroll(false);
   /* And Disable scroll on the body */

 });
    // Menginisialisasi tooltip di halaman
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  </script>

  @yield('js')
  @stack('scripts')

</body>

</html>
