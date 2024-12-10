<!-- <div class="card mx-auto h-100% overflow-hidden" style="height: calc(100vh - 130px);"> -->
<div class="card mx-auto overflow-hidden" id="cartContainer">
  <div class="card-body d-flex flex-column h-100">
    <!-- Header -->
    <div class="header mb-1">
      <div class="row align-items-center">
        <form class="d-flex w-100" id="searchForm">
          <input id="searchInput" class="form-control me-2" type="search" placeholder="Masukan nama Produk / SKU / Scan Barcode" aria-label="Search">
          <input type="hidden" id="storeId" value="{{ $id }}">
        </form>
      </div>
    </div>
    <!-- Garis Pembatas -->
    <hr class="custom-divider">
    <!-- Products -->
    <div class="products flex-grow overflow-auto mb-3" id="productResults">
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2 custom-row">
        @foreach($products as $product)
        <div class="col">
          <div class="card h-100 shadow-sm product-card position-relative" data-product-id="{{ $product->id }}" data-stock="{{ $product->stock }}" style="cursor: pointer;">
            <span class="position-absolute top-0 end-0 badge bg-primary m-2">
              {{ $product->stock }}
            </span>
            @if($product->image_url)
            <img src="{{ asset('storage/' . $product->image_url) }}" class="card-img-top" alt="Gambar Produk">
            @else
            <img src="https://placehold.co/400x300?text=No Image" class="card-img-top" alt="Placeholder">
            @endif
            <div class="card-body p-2">
              <h6 class="card-title mb-1 text-sm" style="font-size: 14px;">
                {{ \Illuminate\Support\Str::limit($product->product_name, 30) }}
              </h6>
              @if($product->has_varian == 'Y')
              <p class="card-text mb-1 text-sm" style="font-size: 12px;">
                {{ $product->variant1 }} {{ $product->variant2 }} {{ $product->variant3 }}
              </p>
              @endif
              <p class="card-text mb-1 text-sm" style="font-size: 12px;">
                <strong>Rp {{ number_format($product->sale_price, 0, ',', '.') }}</strong>
              </p>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>

    <!-- Footer: Page navigation -->
    @if($products->hasPages())
    <div class="footer mt-4">
      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center mb-0">
          {{-- Previous Page Link --}}
          @if ($products->onFirstPage())
          <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
            <span class="page-link" aria-hidden="true">&lsaquo;</span>
          </li>
          @else
          <li class="page-item">
            <a class="page-link" href="{{ $products->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
          </li>
          @endif

          @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
          @if ($page == $products->currentPage())
          <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
          @else
          <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
          @endif
          @endforeach

          @if ($products->hasMorePages())
          <li class="page-item">
            <a class="page-link" href="{{ $products->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
          </li>
          @else
          <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
            <span class="page-link" aria-hidden="true">&rsaquo;</span>
          </li>
          @endif
        </ul>
      </nav>
    </div>
    @endif
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const beepSound = document.getElementById('beepSound');
    const productResults = document.getElementById('productResults');
    const storeId = document.getElementById('storeId').value;

    // Event listener untuk input dari scanner atau keyboard
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function() {
      performSearch();
    });

    // Muat produk secara otomatis saat halaman pertama kali dimuat
    loadInitialProducts();

    function loadInitialProducts() {
      // Lakukan fetch untuk menampilkan produk dari toko tanpa perlu pencarian
      fetch(`/search-products-by-store/${storeId}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
          name: '' // Kosongkan nama untuk menampilkan semua produk
        })
      })
      .then(response => response.json())
      .then(data => {
        updateProductResults(data.products); // Tampilkan produk saat halaman dimuat
      })
      .catch(error => {
        console.error('Error:', error);
      });
    }

    function performSearch() {
      const searchTerm = searchInput.value;

      fetch(`/search-products-by-store/${storeId}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
          name: searchTerm
        })
      })
      .then(response => response.json())
      .then(data => {
        const products = data.products;

        if (products.length === 1) {
          const product = products[0];
          addProductToCart(product.id, product.stock);
          searchInput.value = ''; // Kosongkan input pencarian setelah produk ditambahkan
        } else {
          updateProductResults(products);
        }
      })
      .catch(error => {
        console.error('Error:', error);
      });
    }

    function updateProductResults(products) {
      let html = '<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2 custom-row">';
      products.forEach(product => {
        html += `
          <div class="col">
            <div class="card h-100 shadow-sm product-card position-relative" data-product-id="${product.id}" data-stock="${product.stock}" style="cursor: pointer;">
              <span class="position-absolute top-0 end-0 badge bg-primary m-2">${product.stock}</span>
              ${product.image_url 
                ? `<img src="/storage/${product.image_url}" class="card-img-top" alt="Gambar Produk">`
                : `<img src="https://placehold.co/400x300?text=No Image" class="card-img-top" alt="Placeholder">`
              }
              <div class="card-body p-2">
                <h6 class="card-title mb-1 text-sm">${product.product_name}</h6>
                ${product.has_varian == 'Y'
                  ? `<p class="card-text mb-1 text-sm">
                      Variasi: ${product.variant1} ${product.variant2} ${product.variant3}
                    </p>` 
                  : ''
                }
                <p class="card-text mb-1 text-sm">
                  <strong>Rp ${new Intl.NumberFormat('id-ID').format(product.sale_price)}</strong>
                </p>
              </div>
            </div>
          </div>
        `;
      });
      html += '</div>';
      productResults.innerHTML = html;

      // Tambahkan event listener untuk setiap kartu produk setelah produk ditampilkan
      document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', function() {
          const productId = this.getAttribute('data-product-id');
          const stock = this.getAttribute('data-stock');
          addProductToCart(productId, stock);
          searchInput.value = ''; // Kosongkan input pencarian setelah produk dipilih
        });
      });
    }

    function addProductToCart(productId, stock) {
      fetch(`/get-cart-quantity/${productId}`)
        .then(response => response.json())
        .then(data => {
          const currentQuantity = data.quantity;
          if (currentQuantity >= stock) {
            alert('Stok produk tidak mencukupi');
            return;
          }

          fetch(`/add-to-cart/${productId}`, {
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
              document.dispatchEvent(new Event('productAdded'));
              beepSound.play();
            } else {
              alert('Gagal menambahkan produk ke keranjang');
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
});


</script>