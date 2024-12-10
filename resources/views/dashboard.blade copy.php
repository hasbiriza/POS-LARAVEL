@extends('template.app')
@section('title','Dashboard')
@section('content')
<div class="row">
  <div class="col-xxl-8 mb-4 order-0">
    <div class="card">
      <div class="d-flex align-items-start row">

        <div class="col-sm-7">
          <div class="card-body">
            <h5 class="card-title text-primary mb-3">Selamat Datang, Johan! 👋</h5>
            <p class="mb-2">
              Senang melihatmu kembali! Kami harap harimu produktif dan penuh semangat.
            </p>
            <p class="mb-2">
              Gunakan menu cepat di bawah untuk akses fitur penting:
            </p>
            <!-- Menu Cepat di dalam card selamat datang -->
            <div class="mt-4">
              <a href="products" class="btn btn-outline-primary btn-icon btn-md" data-bs-toggle="tooltip" title="Menu Produk">
                <i class="bx bx-box"></i>
              </a>
              <a href="sales" class="btn btn-outline-success btn-icon btn-md" data-bs-toggle="tooltip" title="Menu Penjualan">
                <i class="bx bx-dollar-circle"></i>
              </a>
              <a href="purchases" class="btn btn-outline-warning btn-icon btn-md" data-bs-toggle="tooltip" title="Menu Pembelian">
                <i class="bx bx-shopping-bag"></i>
              </a>
              <a href="stock-transfers" class="btn btn-outline-info btn-icon btn-md" data-bs-toggle="tooltip" title="Menu Transfer Stok">
                <i class="bx bx-transfer"></i>
              </a>
              <a href="stock-opname" class="btn btn-outline-secondary btn-icon btn-md" data-bs-toggle="tooltip" title="Menu Stock Opname">
                <i class="bx bx-check-circle"></i>
              </a>
              <a href="expenses" class="btn btn-outline-danger btn-icon btn-md" data-bs-toggle="tooltip" title="Menu Biaya Pengeluaran">
                <i class="bx bx-money"></i>
              </a>
              <a href="sales-return" class="btn btn-outline-dark btn-icon btn-md" data-bs-toggle="tooltip" title="Menu Retur Penjualan">
                <i class="bx bx-undo"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="col-sm-5">
          <div class="card-body d-flex justify-content-center pb-0 px-0 px-md-6">
            <img src="{{ asset('assets/img/illustrations/man-with-laptop.png') }}" style="max-height: 175px; width: auto;" class="scaleX-n1-rtl" alt="Ilustrasi Pengguna">
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="col-lg-4 col-md-4 order-1">
    <div class="row">
      <div class="col-lg-6 col-md-12 mb-4 col-6">
        <div class="card h-100">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between mb-4">
              <div class="avatar flex-shrink-0">
                <i class="bx bx-line-chart bx-lg icon-dashboard" style="color: rgba(0, 123, 255, 0.6);"></i>
              </div>
            </div>
            <p class="mb-1">Total Penjualan</p>
            <h4 class="card-title mb-3">Rp1.000.000</h4>
            <small class="text-success fw-medium"><i class='bx bx-up-arrow-alt'></i> +72.80%</small>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-12 mb-4 col-6">
        <div class="card h-100">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between mb-4">
              <div class="avatar flex-shrink-0">
                <i class="bx bx-wallet bx-lg icon-dashboard" style="color: rgba(220, 53, 69, 0.6);"></i>
              </div>
            </div>
            <p class="mb-1">Total Pembelian</p>
            <h4 class="card-title mb-3">Rp10.000.000</h4>
            <small class="text-success fw-medium"><i class='bx bx-up-arrow-alt'></i> +28.42%</small>
          </div>
        </div>
      </div>
    </div>
  </div>






  <!-- Penjualan 30 terakhir -->
  <div class="col-12 col-xxl-8 order-2 order-md-3 mb-4 order-xxl-2 mb-6">
    <div class="card">
      <div class="row row-bordered g-0">
        <div class="col-lg-8">
          <div class="card-header d-flex align-items-center justify-content-between">
            <div class="card-title mb-0">
              <h5 class="m-0 me-2">Penjualan 30 terakhir</h5>
            </div>
            <div class="dropdown">
              <button class="btn p-0" type="button" id="totalRevenue" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="bx bx-dots-vertical-rounded text-muted"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="totalRevenue">
                <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                <a class="dropdown-item" href="javascript:void(0);">Toko A</a>
                <a class="dropdown-item" href="javascript:void(0);">Toko B</a>
              </div>
            </div>
          </div>
          <div id="totalRevenueChart" class="px-3"></div>
        </div>
        <div class="col-lg-4 d-flex align-items-center">
          <div class="card-body px-xl-9">
            <div class="text-center mb-6">
              <div class="btn-group">
                <button type="button" class="btn btn-outline-primary">
                  <script>
                    document.write(new Date().getFullYear() - 1)
                  </script>
                </button>
                <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                  <span class="visually-hidden">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="javascript:void(0);">2021</a></li>
                  <li><a class="dropdown-item" href="javascript:void(0);">2020</a></li>
                  <li><a class="dropdown-item" href="javascript:void(0);">2019</a></li>
                </ul>
              </div>
            </div>

            <div id="growthChart"></div>
            <div class="text-center fw-medium my-6">62% Company Growth</div>

            <div class="d-flex gap-3 justify-content-between">
              <div class="d-flex">
                <div class="avatar me-2">
                  <span class="avatar-initial rounded-2 bg-label-primary"><i class="bx bx-dollar bx-lg text-primary"></i></span>
                </div>
                <div class="d-flex flex-column">
                  <small>
                    <script>
                      document.write(new Date().getFullYear() - 1)
                    </script>
                  </small>
                  <h6 class="mb-0">$32.5k</h6>
                </div>
              </div>
              <div class="d-flex">
                <div class="avatar me-2">
                  <span class="avatar-initial rounded-2 bg-label-info"><i class="bx bx-wallet bx-lg text-info"></i></span>
                </div>
                <div class="d-flex flex-column">
                  <small>
                    <script>
                      document.write(new Date().getFullYear() - 2)
                    </script>
                  </small>
                  <h6 class="mb-0">$41.2k</h6>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Total Revenue -->
  <div class="col-12 col-md-8 col-lg-12 col-xxl-4 mb-4 order-3 order-md-2">
    <div class="row">
      <div class="col-6 mb-6 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between mb-4">
              <div class="avatar flex-shrink-0">
                <i class="bx bx-minus-circle bx-lg icon-dashboard" style="color: rgba(220, 53, 69, 0.6);"></i>
              </div>
            </div>
            <p class="mb-1">Total Pengeluaran</p>
            <h4 class="card-title mb-3">Rp500.000</h4>
            <small class="text-danger fw-medium"><i class='bx bx-down-arrow-alt'></i> -14.82%</small>
          </div>
        </div>
      </div>
      <div class="col-6 mb-6 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between mb-4">
              <div class="avatar flex-shrink-0">
                <i class="bx bx-refresh bx-lg icon-dashboard" style="color: rgba(0, 123, 255, 0.6);"></i>
              </div>
            </div>
            <p class="mb-1">Retur Penjualan</p>
            <h4 class="card-title mb-3">Rp2.000.000</h4>
            <small class="text-success fw-medium"><i class='bx bx-up-arrow-alt'></i> +28.14%</small>
          </div>
        </div>
      </div>
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-sm-row flex-column gap-10">
              <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                <div class="card-title mb-6">
                  <h5 class="text-nowrap mb-1">Profile Report</h5>
                  <span class="badge bg-label-warning">YEAR 2022</span>
                </div>
                <div class="mt-sm-auto">
                  <span class="text-success text-nowrap fw-medium"><i class='bx bx-up-arrow-alt'></i> 68.2%</span>
                  <h4 class="mb-0">$84,686k</h4>
                </div>
              </div>
              <div id="profileReportChart"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <!-- Order Statistics -->
  <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-1 me-2">Penjualan Kategori</h5>
          <p class="card-subtitle">42.82k Total Sales</p>
        </div>
        <div class="dropdown">
          <button class="btn text-muted p-0" type="button" id="orederStatistics" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="bx bx-dots-vertical-rounded"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
            <a class="dropdown-item" href="javascript:void(0);">Select All</a>
            <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
            <a class="dropdown-item" href="javascript:void(0);">Share</a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-6">
          <div class="d-flex flex-column align-items-center gap-1">
            <h3 class="mb-1">8,258</h3>
            <small>Total Orders</small>
          </div>
          <div id="orderStatisticsChart"></div>
        </div>
        <ul class="p-0 m-0">
          <li class="d-flex align-items-center mb-5">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-primary"><i class='bx bx-mobile-alt'></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">Electronic</h6>
                <small>Mobile, Earbuds, TV</small>
              </div>
              <div class="user-progress">
                <h6 class="mb-0">82.5k</h6>
              </div>
            </div>
          </li>
          <li class="d-flex align-items-center mb-5">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-success"><i class='bx bx-closet'></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">Fashion</h6>
                <small>T-shirt, Jeans, Shoes</small>
              </div>
              <div class="user-progress">
                <h6 class="mb-0">23.8k</h6>
              </div>
            </div>
          </li>
          <li class="d-flex align-items-center mb-5">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-info"><i class='bx bx-home-alt'></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">Decor</h6>
                <small>Fine Art, Dining</small>
              </div>
              <div class="user-progress">
                <h6 class="mb-0">849k</h6>
              </div>
            </div>
          </li>
          <li class="d-flex align-items-center">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-secondary"><i class='bx bx-football'></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">Sports</h6>
                <small>Football, Cricket Kit</small>
              </div>
              <div class="user-progress">
                <h6 class="mb-0">99</h6>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <!--/ Order Statistics -->

  <!-- Expense Overview -->
  <div class="col-md-6 col-lg-4 order-1 mb-4">
    <div class="card h-100">
      <div class="card-header nav-align-top">
        <ul class="nav nav-pills" role="tablist">
          <li class="nav-item">
            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-tabs-line-card-income" aria-controls="navs-tabs-line-card-income" aria-selected="true">Income</button>
          </li>
          <li class="nav-item">
            <button type="button" class="nav-link" role="tab">Expenses</button>
          </li>
          <li class="nav-item">
            <button type="button" class="nav-link" role="tab">Profit</button>
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content p-0">
          <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
            <div class="d-flex mb-6">
              <div class="avatar flex-shrink-0 me-3">
                <img src="{{asset('assets/img/icons/unicons/wallet.png')}}" alt="User">
              </div>
              <div>
                <p class="mb-0">Total Balance</p>
                <div class="d-flex align-items-center">
                  <h6 class="mb-0 me-1">$459.10</h6>
                  <small class="text-success fw-medium">
                    <i class='bx bx-chevron-up bx-lg'></i>
                    42.9%
                  </small>
                </div>
              </div>
            </div>
            <div id="incomeChart"></div>
            <div class="d-flex align-items-center justify-content-center mt-6 gap-3">
              <div class="flex-shrink-0">
                <div id="expensesOfWeek"></div>
              </div>
              <div>
                <h6 class="mb-0">Income this week</h6>
                <small>$39k less than last week</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Expense Overview -->

  <!-- Transactions -->
  <div class="col-md-6 col-lg-4 order-2 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0 me-2">Penjualan Produk</h5>
        <div class="dropdown">
          <button class="btn text-muted p-0" type="button" id="transactionID" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="bx bx-dots-vertical-rounded"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
            <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
            <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
            <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
          </div>
        </div>
      </div>
      <div class="card-body pt-4">
        <ul class="p-0 m-0">
          <li class="d-flex align-items-center mb-6">
            <div class="avatar flex-shrink-0 me-3">
              <img src="{{asset('assets/img/icons/unicons/paypal.png')}}" alt="User" class="rounded">
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <small class="d-block">Untuk Nama Produk 1</small>
                <h6 class="fw-normal mb-0">Merah, S</h6>
              </div>
              <div class="user-progress d-flex align-items-center gap-2">
                <h6 class="fw-normal mb-0">Rp 150.000</h6>
              </div>
            </div>
          </li>
          <li class="d-flex align-items-center mb-6">
            <div class="avatar flex-shrink-0 me-3">
              <img src="{{asset('assets/img/icons/unicons/wallet.png')}}" alt="User" class="rounded">
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <small class="d-block">Untuk Nama Produk 1</small>
                <h6 class="fw-normal mb-0">Merah, S</h6>
              </div>
              <div class="user-progress d-flex align-items-center gap-2">
                <h6 class="fw-normal mb-0">Rp 745.000</h6>
              </div>
            </div>
          </li>
          <li class="d-flex align-items-center mb-6">
            <div class="avatar flex-shrink-0 me-3">
              <img src="{{asset('assets/img/icons/unicons/chart.png')}}" alt="User" class="rounded">
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <small class="d-block">Untuk Nama Produk 1</small>
                <h6 class="fw-normal mb-0">Merah, S</h6>
              </div>
              <div class="user-progress d-flex align-items-center gap-2">
                <h6 class="fw-normal mb-0">Rp 680.000</h6>
              </div>
            </div>
          </li>
          <li class="d-flex align-items-center mb-6">
            <div class="avatar flex-shrink-0 me-3">
              <img src="{{asset('assets/img/icons/unicons/cc-primary.png')}}" alt="User" class="rounded">
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <small class="d-block">Untuk Nama Produk 1</small>
                <h6 class="fw-normal mb-0">Merah, S</h6>
              </div>
              <div class="user-progress d-flex align-items-center gap-2">
                <h6 class="fw-normal mb-0">Rp 120.000</h6>
              </div>
            </div>
          </li>
          <li class="d-flex align-items-center mb-6">
            <div class="avatar flex-shrink-0 me-3">
              <img src="{{asset('assets/img/icons/unicons/wallet.png')}}" alt="User" class="rounded">
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <small class="d-block">Untuk Nama Produk 1</small>
                <h6 class="fw-normal mb-0">Merah, S</h6>
              </div>
              <div class="user-progress d-flex align-items-center gap-2">
                <h6 class="fw-normal mb-0">Rp 1.150.000</h6>
              </div>
            </div>
          </li>
          <li class="d-flex align-items-center">
            <div class="avatar flex-shrink-0 me-3">
              <img src="{{asset('assets/img/icons/unicons/cc-warning.png')}}" alt="User" class="rounded">
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <small class="d-block">Untuk Nama Produk 1</small>
                <h6 class="fw-normal mb-0">Merah, S</h6>
              </div>
              <div class="user-progress d-flex align-items-center gap-2">
                <h6 class="fw-normal mb-0">Rp 200.000</h6>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <!--/ Transactions -->
</div>
@endsection