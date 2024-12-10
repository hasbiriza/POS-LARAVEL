<!-- <div class="card mx-auto h-100% overflow-hidden" style="height: calc(100vh - 130px);"> -->
<div class="card mx-auto overflow-hidden" id="cartContainer">
  <div class="card-body d-flex flex-column h-100">
    <!-- Header -->
    <div class="header mb-3">
      <div class="row align-items-center">

      <div class="col-md-1 header-menu-cart">
          <button class="btn btn-primary custom-menu-button btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#menuModal" id="openMenuModal"> <!-- Tambahkan kelas small di sini -->
            <i class="bx bxs-category"></i>
          </button>
        </div>

        <div class="col-md-7 header-search-cart">
          <div class="btn-group">
          <button id="searchButton" class="btn btn-success custom-menu-button btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#searchModal">
            <i class="bx bx-search"></i>
          </button>
            <!-- <input id="searchInputcart" class="form-control form-control-sm" type="search" placeholder="Cari produk" aria-label="Search"> -->
            <button id="barcodeScanner" class="btn btn-primary custom-menu-button btn-sm" type="button">
              <i class="bx bx-barcode-reader"></i>
            </button>
          </div>
          <video id="barcodeVideo" style="display: none;"></video>
          <button id="closeBarcodeVideo" style="display: none;">&times;</button>
          <input type="hidden" id="storeIdcart" value="{{ $id }}">
        </div>

        <div class="col-md-4 header-customer-cart">
          <div class="input-group input-group-sm"> <!-- Tambahkan kelas small di sini -->
            <span class="input-group-text">
              <i class="bx bx-user custom-menu-icon"></i>
            </span>
            <x-select2 class="form-select form-select-sm" id="customerSelectcart" style="border: 10px solid #ced4da;">
              <option value="">Pilih Pelanggan</option>
              @foreach($customers as $customer)
              <option value="{{ $customer->id }}" {{ $customer->name == 'guest' ? 'selected' : '' }}>
                {{ $customer->name }}
              </option>
              @endforeach
            </x-select2>
          </div>
        </div>

      </div>
    </div>

<!-- Garis Pembatas -->
<hr class="custom-divider">

    <!-- Table Produk -->
    <div class="table-container">

      @include('sales-transaction.cart-items')
    </div>
  </div>

  <!-- Footer -->
  <div class="card-footer mt-auto">
    <div class="row">
      <div class="col-6">
        <h5>Total Item: <span id="totalItems">{{ null !== Cart::count() ? Cart::count() : 0 }}</span></h5>
      </div>
      <div class="col-6 text-end">
        <h5>Total: Rp <span id="cartTotal">{{ null !== Cart::total() ? number_format((float)Cart::total(), 0, ',', '.') : '0' }}</span></h5>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-6">
        <div class="form-group">
          <label for="discount">Diskon (%)</label>
          <div class="input-group">
            <span class="input-group-text" style="background-color: #f8f9fa;">
              <i class='bx bxs-discount' style="color: #ff5733;"></i>
            </span>
            <input type="number" class="form-control" id="discount" placeholder="Masukkan diskon" min="0" max="100" value="0">
          </div>
        </div>
      </div>
      <div class="col-6">
        <div class="form-group">
          <label for="tax">Pajak (%)</label>
          <div class="input-group">
            <span class="input-group-text" style="background-color: #f8f9fa;">
              <i class='bx bxs-offer' style="color: #28a745;"></i>
            </span>
            <select class="form-select" id="tax">
              <option value="0" selected>Pilih Pajak</option>
              @foreach($pajak as $p)
              <option value="{{ $p->discount_value }}">{{ $p->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-3" style="display: none;">
      <div class="col-12">
        <h5>Total Pembayaran: Rp <span id="totalPayment">0</span></h5>
      </div>
    </div>
    <div class="row mt-3">
      <div class="d-flex justify-content-between">
      <div class="btn-group w-100">
        <button id="deleteAllButton" class="btn btn-danger" style="flex: 0 0 10%; font-size: 1rem; padding: 0.75rem 1.5rem;" {{ Cart::count() > 0 ? '' : 'disabled' }}>
          <i class="bx bx-trash"></i>
        </button>
        <button id="payButton" class="btn btn-primary flex-fill" style="font-size: 1rem; padding: 0.75rem 1.5rem;" {{ Cart::count() > 0 ? '' : 'disabled' }} data-bs-toggle="modal" data-bs-target="#paymentModal">
          Bayar
        </button>
      </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal Pembayaran -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">Pembayaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="paymentForm" action="{{ route('confirm-payment') }}" method="POST">
          @csrf
          <input type="hidden" id="storeIdInput" name="store_id">
          <input type="hidden" id="customerIdInput" name="customer_id">
          <input type="hidden" id="discountAmountInput" name="discount_amount">
          <input type="hidden" id="taxAmountInput" name="tax_amount">
          <div class="mb-3">
            <label for="totalAmount" class="form-label">Total Pembayaran</label>
            <input type="text" class="form-control" id="totalAmount" name="total_amount" readonly>
          </div>
          <div class="mb-3">
            <label for="paymentMethod" class="form-label">Metode Pembayaran</label>
            <select class="form-select" id="paymentMethod" name="payment_method">
              <option value="cash">Tunai</option>
              <option value="bank_transfer">Transfer Bank</option>
              <option value="tempo">Tempo</option>
            </select>
          </div>
          <div class="mb-3" id="bankAccountDiv" style="display: none;">
            <label for="bankAccount" class="form-label">Akun Bank</label>
            <select class="form-select" id="bankAccount" name="bank_account">
              @foreach($banks as $bank)
              <option value="{{ $bank->id }}">{{ $bank->name }} - {{ $bank->account_number }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="amountPaid" class="form-label">Jumlah Dibayar</label>
            <input type="number" class="form-control" id="amountPaid" name="amountPaid" required>
          </div>
          <div class="mb-3">
            <label for="change" class="form-label">Kembalian</label>
            <input type="text" class="form-control" id="change" name="change" readonly>
          </div>
          <div class="modal-footer">
          <div class="btn-group">
            <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary" id="confirmPayment">Konfirmasi Pembayaran</button>
          </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal pilh toko -->
<div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="menuModalLabel">Pilih Toko</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="storeForm" action="" method="GET">
        <div class="modal-body">
          <select class="form-select me-2" id="storeSelect" name="store_id" style="border: 1px solid #ced4da;">
            <option value="">Pilih Toko</option>
            @foreach($stores as $store)
            <option value="{{ $store->id }}" {{ isset($id) && $id == $store->id ? 'selected' : '' }}>{{ $store->store_name }}</option>
            @endforeach
          </select>
          <div class="form-group mt-3">
            <label for="transactionDate">Tanggal Transaksi</label>
            <input type="date" class="form-control" id="transactionDate" name="transactionDate" value="{{ date('Y-m-d') }}" readonly>
          </div>

          <div class="form-group mt-3">
            <label for="invoiceNumber">No. Invoice</label>
            <input type="text" class="form-control" id="invoiceNumber" name="invoiceNumber" readonly>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary" id="submitTransaction">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Search Product -->
<input type="hidden" id="store" value="{{ $id }}">
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="searchModalLabel">Cari Produk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <x-select2 class="form-control select2" id="productSearch" name="product" placeholder="Cari produk..."></x-select2>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const beepSound = document.getElementById('beepSound');

    function showCart() {
      fetch('{{ route("cart-content") }}')
        .then(response => response.json())
        .then(data => {
          document.getElementById('cartItems').innerHTML = data.cartItemsHtml;
          document.getElementById('totalItems').textContent = data.count;
          document.getElementById('cartTotal').textContent = data.total;

          const payButton = document.getElementById('payButton');
          const deleteAllButton = document.getElementById('deleteAllButton');
          if (data.count > 0) {
            payButton.removeAttribute('disabled');
            deleteAllButton.removeAttribute('disabled');
          } else {
            payButton.setAttribute('disabled', 'disabled');
            deleteAllButton.setAttribute('disabled', 'disabled');
          }

          calculateTotalPayment();
        });
    }

    function calculateTotalPayment() {
      const cartTotal = parseFloat(document.getElementById('cartTotal').textContent.replace(/\./g, '').replace(',', '.'));
      const discountPercentage = parseFloat(document.getElementById('discount').value) || 0;
      const taxPercentage = parseFloat(document.getElementById('tax').value) || 0;

      const discountAmount = cartTotal * (discountPercentage / 100);
      const subtotalAfterDiscount = cartTotal - discountAmount;
      const taxAmount = subtotalAfterDiscount * (taxPercentage / 100);
      const totalPayment = subtotalAfterDiscount + taxAmount;

      document.getElementById('totalPayment').textContent = totalPayment.toLocaleString('id-ID');
      document.getElementById('totalAmount').value = totalPayment.toLocaleString('id-ID');
      document.getElementById('discountAmountInput').value = discountAmount.toFixed(2);
      document.getElementById('taxAmountInput').value = taxAmount.toFixed(2);
    }

    showCart();

    document.addEventListener('productAdded', function() {
      showCart();
    });

    document.getElementById('discount').addEventListener('input', calculateTotalPayment);
    document.getElementById('tax').addEventListener('change', calculateTotalPayment);

    document.getElementById('deleteAllButton').addEventListener('click', function() {
      if (confirm('Apakah Anda yakin ingin menghapus semua item dari keranjang?')) {
        fetch('{{ route("delete-all-cart") }}', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              showCart();
              document.getElementById('discount').value = 0;
              document.getElementById('tax').value = 0;
            } else {
              alert('Gagal menghapus semua item dari keranjang');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus semua item dari keranjang');
          });
      }
    });

    document.getElementById('amountPaid').addEventListener('input', function() {
      const totalPayment = parseFloat(document.getElementById('totalPayment').textContent.replace(/\./g, '').replace(',', '.'));
      const amountPaid = parseFloat(this.value) || 0;
      const change = amountPaid - totalPayment;

      if (change >= 0) {
        document.getElementById('change').value = change.toLocaleString('id-ID');
      } else {
        document.getElementById('change').value = '0';
      }
    });

    document.getElementById('paymentForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const formData = new FormData(this);
      formData.append('store_id', document.getElementById('storeIdcart').value);
      formData.append('customer_id', document.getElementById('customerSelectcart').value);
      const totalAmount = document.getElementById('totalAmount').value;
      formData.set('total_amount', parseFloat(totalAmount.replace(/\./g, '').replace(',', '.')));
      const amount_paid = document.getElementById('amountPaid').value;
      formData.set('amount_paid', parseFloat(amount_paid.replace(/\./g, '').replace(',', '.')));
      const change_payment = document.getElementById('change').value;
      formData.set('change_payment', parseFloat(change_payment.replace(/\./g, '').replace(',', '.')));

      console.log(formData);
      $.ajax({
        url: this.action,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        },
        success: function(data) {
          if (data.success) {
            $('#paymentModal').modal('hide');
            showCart();
            document.getElementById('discount').value = 0;
            document.getElementById('tax').value = 0;
            document.getElementById('totalAmount').value = '';
            document.getElementById('storeSelect').value = '';
            document.getElementById('customerSelectcart').value = '';
            document.getElementById('amountPaid').value = '';
            document.getElementById('change').value = '';
            window.open(data.nota_url, '_blank');
            location.reload();
          } else {
            alert('Gagal mengkonfirmasi pembayaran: ' + data.message);
          }
        },
        error: function(xhr, status, error) {
          console.error('Error:', error);
          alert('Terjadi kesalahan saat mengkonfirmasi pembayaran');
        }
      });
    });

    $('#storeForm').on('submit', function(e) {
      e.preventDefault();
      var selectedStoreId = $('#storeSelect').val();
      if (selectedStoreId) {
        $.ajax({
          url: '{{ route("destroy-cart") }}',
          method: 'GET',
          success: function() {
            window.location.href = '{{ route("sales-transaction.byStore", ["id" => ":id"]) }}'.replace(':id', selectedStoreId);
          },
          error: function(xhr, status, error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus keranjang');
          }
        });
      } else {
        alert('Silakan pilih toko terlebih dahulu');
      }
    });

    document.getElementById('storeSelect').addEventListener('change', function() {
      var selectedStoreId = this.value;
      if (selectedStoreId) {
        fetch('{{ route("get-latest-transaction", ["storeId" => ":storeId"]) }}'.replace(':storeId', selectedStoreId))
          .then(response => response.json())
          .then(data => {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();
            var latestId = String(data.latest_id + 1).padStart(2, '0');
            var invoiceNumber = 'INV-' + yyyy + mm + dd + '-' + latestId;
            document.getElementById('invoiceNumber').value = invoiceNumber;
          });
      } else {
        document.getElementById('invoiceNumber').value = '';
      }
    });

    document.getElementById('paymentMethod').addEventListener('change', function() {
      var bankAccountDiv = document.getElementById('bankAccountDiv');
      var amountPaidInput = document.getElementById('amountPaid');

      if (this.value === 'bank_transfer') {
        bankAccountDiv.style.display = 'block';
      } else {
        bankAccountDiv.style.display = 'none';
      }

      if (this.value === 'tempo') {
        amountPaidInput.value = '0';
        amountPaidInput.setAttribute('readonly', true);
        document.getElementById('change').value = '0';
      } else {
        amountPaidInput.value = '';
        amountPaidInput.removeAttribute('readonly');
        document.getElementById('change').value = '';
      }
    });

    // search or input with scan barcode
    document.getElementById('barcodeScanner').addEventListener('click', function() {
      if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({
            video: {
              facingMode: "environment",
              focusMode: "continuous",
              width: {
                ideal: 640
              },
              height: {
                ideal: 480
              }
            }
          })
          .then(function(stream) {
            const barcodeVideo = document.getElementById('barcodeVideo');
            const closeBarcodeVideo = document.getElementById('closeBarcodeVideo');

            barcodeVideo.srcObject = stream;
            barcodeVideo.style.display = 'block';
            closeBarcodeVideo.style.display = 'block';
            barcodeVideo.play();

            Quagga.init({
              inputStream: {
                name: "Live",
                type: "LiveStream",
                target: barcodeVideo,
                constraints: {
                  width: {
                    min: 320
                  },
                  height: {
                    min: 240
                  },
                  facingMode: "environment",
                  aspectRatio: {
                    min: 1,
                    max: 2
                  }
                }
              },
              locator: {
                patchSize: "small",
                halfSample: false
              },
              numOfWorkers: 2,
              decoder: {
                readers: ["ean_reader", "ean_8_reader", "code_128_reader"],
                multiple: false
              },
              locate: true,
              frequency: 10
            }, function(err) {
              if (err) {
                console.error(err);
                return;
              }
              Quagga.start();
            });

            let lastDetectedCode = null;
            let lastDetectedTime = 0;
            let scannedBarcodes = new Set();

            Quagga.onDetected(function(result) {
              const code = result.codeResult.code;
              const currentTime = new Date().getTime();

              if ((code !== lastDetectedCode || currentTime - lastDetectedTime > 2000) && !scannedBarcodes.has(code)) {
                lastDetectedCode = code;
                lastDetectedTime = currentTime;
                scannedBarcodes.add(code);

                addProductToCartBarcode(code);

                Quagga.stop();
                stream.getTracks().forEach(track => track.stop());
                barcodeVideo.style.display = 'none';
                closeBarcodeVideo.style.display = 'none';
              }
            });

            // Fungsi untuk menutup video jika tombol "X" ditekan
            closeBarcodeVideo.addEventListener('click', function() {
              Quagga.stop();
              stream.getTracks().forEach(track => track.stop());
              barcodeVideo.style.display = 'none';
              closeBarcodeVideo.style.display = 'none';
            });

          })
          .catch(function(error) {
            alert("Terjadi kesalahan saat mengakses kamera. Silakan coba lagi.");
          });
      } else {
        alert("Maaf, browser Anda tidak mendukung akses kamera.");
      }
    });

    function addProductToCartBarcode(barcode) {
      // alert("dd" +barcode);
      fetch(`{{ route('get-cart-quantity-by-barcode', '') }}/${barcode}`)
        .then(response => response.json())
        .then(data => {
          const currentQuantity = data.quantity;
          const stock = data.stock;
          if (currentQuantity >= stock) {
            alert('Stok produk tidak mencukupi');
            return true;
          }

          fetch(`{{ route('add-to-cart-by-barcode', '') }}/${barcode}`, {
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
              }
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                beepSound.play();
                document.dispatchEvent(new Event('productAdded'));
              } else {
                alert(data.message);
              }
            })
            .catch(error => {
              console.error('Error:', error);
              alert('Terjadi kesalahan saat menambahkan produk ke keranjang');
            });
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Terjadi kesalahan saat memeriksa kuantitas produk di keranjang');
        });
    }

    // Shortcut
    document.addEventListener('keydown', function(event) {
      // Shortcut untuk tombol kategori
    if (event.altKey && event.key === 's') {
      event.preventDefault(); // Mencegah aksi default dari kombinasi Alt + C
      document.getElementById('openMenuModal').click(); // Klik tombol kategori secara otomatis
    }

    // Shortcut untuk dropdown pelanggan
    if (event.altKey && event.key === 'c') {
      event.preventDefault(); // Mencegah aksi default dari kombinasi Alt + E
      document.getElementById('customerSelectcart').focus(); // Fokus pada dropdown pelanggan
    }

    // Shortcut untuk tombol bayar
    if (event.altKey && event.key === 'b') {
      event.preventDefault(); // Mencegah aksi default dari kombinasi Alt + B
      document.getElementById('payButton').click(); // Klik tombol Bayar secara otomatis
    }

    // Shortcut untuk tombol hapus semua
    if (event.altKey && event.key === 'd') {
      event.preventDefault(); // Mencegah aksi default dari kombinasi Alt + D
      document.getElementById('deleteAllButton').click(); // Klik tombol Hapus Semua secara otomatis
    }
    
    });

    $('#productSearch').select2({
            placeholder: 'Cari produk...',
            ajax: {
                url: '{{ route("purchase.search") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        name: params.term,
                        store_id: $('#store').val()
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.product_name + ' ' + (item.variasi_1 || '') + ' ' + (item.variasi_2 || '') + ' ' + (item.variasi_3 || ''),
                                id: item.product_pricing_id,
                                data: item
                            }
                        })
                    };
                },
                cache: true
            },
            dropdownParent: $('#searchModal')
        });
        $('#productSearch').on('select2:select', function (e) {
          // console.log(e);
          const barcode = e.params.data.data.barcode;
          // alert("barcode "+barcode);
          addProductToCartBarcode(barcode);
          // $('#productSearch').val(null).trigger('change');
        })

  


  });

</script>