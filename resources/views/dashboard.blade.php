@extends('template.app')
@section('title','Dashboard')
@section('content')
<div class="row g-2">
  <div class="col-xxl-8 mb-2 order-0">
    <div class="card">
      <div class="d-flex align-items-start row">

        <div class="col-sm-7">
          <div class="card-body">
            <h5 class="card-title text-primary mb-3">Selamat Datang, Johan! 👋</h5>
            <p class="mb-1">
              Senang melihatmu kembali! Kami harap harimu produktif dan penuh semangat.
            </p>
            <!-- <p class="mb-2">
              Gunakan menu cepat di bawah untuk akses fitur penting:
            </p> -->
            <!-- Menu Cepat di dalam card selamat datang -->
            <div class="mt-2">
              <!-- <label for="storeSelect" class="form-label">Pilih Toko untuk melihat transaksi hari ini</label> -->
              <x-select2-multiselect>
                @foreach($stores as $store)
                    <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                @endforeach
              </x-select2-multiselect>

              <!-- <a href="products" class="btn btn-outline-primary btn-icon btn-md" data-bs-toggle="tooltip" title="Menu Produk">
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
              </a> -->

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
  <div class="row g-2">
    <div class="col-lg-6 col-md-12 mb-2 col-6">
      <div class="card h-100 rounded-3 border-0">
        <div class="card-body text-center">
          <div class="card-title d-flex align-items-start justify-content-center mb-4">
            <div class="avatar flex-shrink-0">
              <i class="bx bx-line-chart bx-lg icon-dashboard" style="color: rgba(0, 123, 255, 0.6);"></i>
            </div>
          </div>
          <p class="mb-1 text-muted">Total Penjualan</p>
          <h4 class="card-title mb-3 text-primary">{{ number_format($totalSalesAll, 0, ',', '.') }}</h4>
          <!-- Optional: Add a percentage change indicator -->
          <small class="text-success fw-medium"><i class='bx bx-up-arrow-alt'></i> +72.80%</small>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-md-12 mb-2 col-6">
      <div class="card h-100 rounded-3 border-0">
        <div class="card-body text-center">
          <div class="card-title d-flex align-items-start justify-content-center mb-4">
            <div class="avatar flex-shrink-0">
              <i class="bx bx-wallet bx-lg icon-dashboard" style="color: rgba(220, 53, 69, 0.6);"></i>
            </div>
          </div>
          <p class="mb-1 text-muted">Total Pembelian</p>
          <h4 class="card-title mb-3 text-danger">{{ number_format($totalPurchaseAll, 0, ',', '.') }}</h4>
          <!-- Optional: Add a percentage change indicator -->
          <small class="text-success fw-medium"><i class='bx bx-up-arrow-alt'></i> +28.42%</small>
        </div>
      </div>
    </div>
  </div>
</div>

  <!-- Penjualan per Bulan -->
  <div class="col-12 col-xxl-8 order-2 order-md-3 mb-2 order-xxl-2 mb-6">
    <div class="card">
        <div class="row g-0">
            <div class="col-lg-12">
                <div class="card-header d-flex align-items-center justify-content-between">
                  
                </div>
                <div id="monthlySalesChart" class="px-3"></div>
            </div>
        </div>
    </div>
</div>

 <!--/ Total Revenue -->
 <div class="col-12 col-md-8 col-lg-12 col-xxl-4 mb-2 order-3 order-md-2">
  <div class="row g-2">
    <div class="col-6 mb-2">
      <div class="card h-100 rounded-3 border-0">
        <div class="card-body text-center">
          <div class="card-title d-flex align-items-start justify-content-center mb-4">
            <div class="avatar flex-shrink-0">
              <i class="bx bx-minus-circle bx-lg icon-dashboard" style="color: rgba(220, 53, 69, 0.6);"></i>
            </div>
          </div>
          <p class="mb-1 text-muted">Total Pengeluaran</p>
          <h4 class="card-title mb-3 text-danger">{{ number_format($totalExpenseAll, 0, ',', '.') }}</h4>
          <!-- Optional: Add a percentage change indicator -->
          <small class="text-danger fw-medium"><i class='bx bx-down-arrow-alt'></i> -14.82%</small>
        </div>
      </div>
    </div>
    <div class="col-6 mb-2">
      <div class="card h-100 rounded-3 border-0">
        <div class="card-body text-center">
          <div class="card-title d-flex align-items-start justify-content-center mb-4">
            <div class="avatar flex-shrink-0">
              <i class="bx bx-refresh bx-lg icon-dashboard" style="color: rgba(0, 123, 255, 0.6);"></i>
            </div>
          </div>
          <p class="mb-1 text-muted">Retur Penjualan</p>
          <h4 class="card-title mb-3 text-primary">{{ number_format($totalReturnAll, 0, ',', '.') }}</h4>
          <!-- Optional: Add a percentage change indicator -->
          <small class="text-success fw-medium"><i class='bx bx-up-arrow-alt'></i> +28.14%</small>
        </div>
      </div>
    </div>
    <!-- Grafik Produk Terlaris -->
    <div class="col-12 mb-2">
      <div class="card rounded-3 border-0">
        <div class="card-body">
            
            <div id="bestSellingProductsChart"></div>
        </div>
      </div>
    </div>
  </div>
</div>


<script>
    // Data untuk Grafik Produk Terlaris
    var options = {
      series: [44, 55, 41, 17, 15], // Data penjualan untuk 5 produk terlaris
      chart: {
        type: 'pie',
        height: 195,
      },
      labels: ['Produk Premium Bergaransi Toko A', 'Produk B', 'Produk C', 'Produk D', 'Produk E'], // Nama produk
      title: {
        text: '5 Produk Terlaris',
        align: 'center',
        style: {
          fontSize: '16px',
          color: '#666'
        }
      },
      colors: ['#FF4560', '#008FFB', '#00E396', '#775DD0', '#FEB019'], // Warna untuk setiap segmen
      responsive: [{
        breakpoint: 480,
        options: {
          chart: {
            width: '100%'
          },
          legend: {
            position: 'bottom'
          }
        }
      }]
    };

    var chart = new ApexCharts(document.querySelector("#bestSellingProductsChart"), options);
    chart.render();
</script>

</div>

<div class="row">
  <div class="col-12 mb-2">
    <div class="card">
      <div class="card-body">
        <div id="dailySalesChart"></div>
      </div>
    </div>
  </div>
</div>


<script>
    // Data penjualan harian dari controller
    var salesDataDaily = {!! json_encode($getsaleschartDaily) !!};

    // Mengambil jumlah hari dalam bulan berjalan
    var currentDate = new Date();
    var totalDaysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();

    // Mendapatkan nama bulan
    var monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    var currentMonthName = monthNames[currentDate.getMonth()]; // Nama bulan saat ini

    // Mengubah data penjualan harian ke format yang sesuai untuk ApexCharts
    var seriesDataDaily = [];
    for (var store in salesDataDaily) {
        if (salesDataDaily.hasOwnProperty(store)) {
            seriesDataDaily.push({
                name: store,    // Nama toko
                data: salesDataDaily[store]   // Data penjualan harian
            });
        }
    }

    var dailySalesOptions = {
        series: seriesDataDaily,  // Gunakan data dari controller
        chart: {
            height: 350,
            type: 'line',
        },
        stroke: {
            width: 7,
            curve: 'smooth'
        },
        xaxis: {
            categories: Array.from({length: totalDaysInMonth}, (_, i) => i + 1), // Hari ke-1 sampai hari terakhir bulan ini
            title: {
                text: 'Hari'
            }
        },
        title: {
            text: 'Penjualan - ' + currentMonthName, // Tambahkan nama bulan ke judul
            align: 'left',
            style: {
                fontSize: "16px",
                color: '#666'
            }
        },
        markers: {
            size: 4,
            colors: ['#FFA41B'],
            strokeColors: '#fff',
            strokeWidth: 2,
            hover: {
                size: 7,
            }
        },
        yaxis: {
            title: {
                text: 'Penjualan (Rp)'
            },
        },
    };

    var dailySalesChart = new ApexCharts(document.querySelector("#dailySalesChart"), dailySalesOptions);
    dailySalesChart.render();
</script>







<div class="row g-2">
  <!-- Order Statistics -->
  <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-2">
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
  <div class="col-md-6 col-lg-4 order-1 mb-2">
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
  <div class="col-md-6 col-lg-4 order-2 mb-2">
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

<script>
    // Data penjualan dinamis dari controller
    var salesData = {!! json_encode($getsaleschart) !!};

    var seriesData = [];
    for (var store in salesData) {
        if (salesData.hasOwnProperty(store)) {
            seriesData.push({
                name: store,
                data: salesData[store]
            });
        }
    }

    var options = {
        series: seriesData, // Gunakan data dari controller
        chart: {
            height: 400,
            type: 'line',
        },
        stroke: {
            width: 7,
            curve: 'smooth'
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            title: {
                text: 'Bulan'
            }
        },
        title: {
            text: 'Penjualan (Jan - Des)',
            align: 'left',
            style: {
                fontSize: "16px",
                color: '#666'
            }
        },
        markers: {
            size: 4,
            colors: ['#FFA41B', '#FF4560', '#4560ff'],
            strokeColors: '#fff',
            strokeWidth: 2,
            hover: {
                size: 7,
            }
        },
        yaxis: {
            title: {
                text: 'Penjualan (Rp)'
            },
        },
        colors: ['#FFA41B', '#FF4560', '#4560ff'],
    };

    var chart = new ApexCharts(document.querySelector("#monthlySalesChart"), options);
    chart.render();
</script>



@endsection