<!-- <div class="card mx-auto h-100% overflow-hidden" style="height: calc(100vh - 130px);"> -->
<div class="card mx-auto overflow-hidden" id="cartContainer">
  <div class="card-body d-flex flex-column h-100">
    <!-- Header -->
    <div class="header mb-3">
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
      <div class="products-grid">
        @foreach($products as $product)
        <div class="product-card">
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
    <div class="footer mt-2">
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
    let isAddingToCart = false;

    function addProductToCart(productId, stock) {
      if (isAddingToCart) return;
      isAddingToCart = true;

      fetch(`/get-cart-quantity/${productId}`)
        .then(response => response.json())
        .then(data => {
          const currentQuantity = data.quantity;
          if (currentQuantity >= stock) {
            alert('Stok produk tidak mencukupi');
            isAddingToCart = false;
            return;
          }

          fetch(`/add-to-cart/${productId}`, {
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
                console.log(data);
                document.dispatchEvent(new Event('productAdded'));
                beepSound.play();

                searchInput.value = '';
                isAddingToCart = false;
              } else {
                alert('Gagal menambahkan produk ke keranjang');
                isAddingToCart = false;
              }
            })
            .catch(error => {
              console.error('Error:', error);
              alert('Terjadi kesalahan saat menambahkan produk ke keranjang');
              isAddingToCart = false;
            });
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Terjadi kesalahan saat memeriksa kuantitas produk di keranjang');
          isAddingToCart = false;
        });
    }

    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
      card.addEventListener('click', function() {
        const currentUrl = window.location.pathname;
        if (currentUrl === '/sales-transaction') {
          alert('Silakan pilih toko terlebih dahulu');
          return;
        }
        const productId = this.getAttribute('data-product-id');
        const stock = parseInt(this.getAttribute('data-stock'));
        addProductToCart(productId, stock);
      });
    });

    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    const productResults = document.getElementById('productResults');
    const storeId = document.getElementById('storeId').value;

    searchForm.addEventListener('submit', function(e) {
      e.preventDefault();
      performSearch();
    });

    searchInput.addEventListener('input', function() {
      performSearch();
    });

    function performSearch() {
      const currentUrl = window.location.pathname;
      if (currentUrl === '/sales-transaction') {
        alert('Silakan pilih toko terlebih dahulu');
        return;
      }
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
          if (data.products.length === 1) {
          const product = data.products[0];
          addProductToCart(product.id, product.stock);
        } else {
          updateProductResults(data.products);
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
              <span class="position-absolute top-0 end-0 badge bg-primary m-2">
                ${product.stock}
              </span>
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

      // Tambahkan event listener untuk produk hasil pencarian
      const newProductCards = document.querySelectorAll('.product-card');
      newProductCards.forEach(card => {
        card.addEventListener('click', function() {
          const productId = this.getAttribute('data-product-id');
          const stock = parseInt(this.getAttribute('data-stock'));
          addProductToCart(productId, stock);
        });
      });
    }

    // Listen for Alt+F to focus on the search input
  document.addEventListener('keydown', function(e) {
    if (e.altKey && e.key === 'f') {
      e.preventDefault();
      const searchInput = document.getElementById('searchInput');
      if (searchInput) {
        searchInput.focus();
      }
    }
  });

  });
</script>