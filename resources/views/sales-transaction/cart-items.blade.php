<div id="cartItems">
  @if(isset($cartItems) && $cartItems->count() > 0)
  @foreach($cartItems as $item)
  <div class="cart-item">
  <div class="item-name" data-bs-toggle="modal" data-bs-target="#itemModal_{{ $item->id }}" style="cursor: pointer;">
  {{ $item->name }}
      @if($item->options->variant1 || $item->options->variant2 || $item->options->variant3)
      <br><small style="color: gray; font-style: italic;">
        {{ $item->options->variant1 }}
        {{ $item->options->variant2 ? ', ' . $item->options->variant2 : '' }}
        {{ $item->options->variant3 ? ', ' . $item->options->variant3 : '' }}
      </small>
      @endif
    </div>
    <div class="item-details">
      <div class="item-quantity">

        <div class="input-group input-group-md" style="width: 75px;">
          <button class="btn btn-outline-secondary btn-minus btn-sm" type="button" style="padding: 2px 5px;">-</button>
          <input type="number" class="form-control qty-input" value="{{ $item->qty }}" min="1" max="{{ $item->options->stock }}" style="width: 30px; text-align: center; padding: 2px;" data-rowid="{{ $item->rowId }}" data-price="{{ $item->price }}" data-stock="{{ $item->options->stock }}">
          <button class="btn btn-outline-secondary btn-plus btn-sm" type="button" style="padding: 2px 5px;">+</button>
        </div>
      </div>

      <div class="item-subtotal">
        <div class="subtotal-container">
          Rp <span class="subtotal-amount">{{ number_format($item->subtotal, 0, ',', '.') }}</span>
        </div>
      </div>
      <div class="item-remove">
        <button class="btn btn-sm btn-danger remove-item" data-rowid="{{ $item->rowId }}" onclick="removeFromCart('{{ $item->rowId }}')">
          <i class="bx bx-trash"></i>
        </button>
      </div>
    </div>
  </div>

   <!-- Modal Structure -->
   <div class="modal fade" id="itemModal_{{ $item->id }}" tabindex="-1" aria-labelledby="itemModalLabel_{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="itemModalLabel_{{ $item->id }}">
                            {{ $item->name }}
                    @if($item->options->variant1 || $item->options->variant2 || $item->options->variant3)
                        <small style="color: gray; font-style: italic;">
                            {{ $item->options->variant1 }}
                            {{ $item->options->variant2 ? ', ' . $item->options->variant2 : '' }}
                            {{ $item->options->variant3 ? ', ' . $item->options->variant3 : '' }}
                        </small>
                    @endif
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <hr style="margin: 0; border: 1px solid #dee2e6;">

                        <div class="modal-body">

                        <div class="mb-3">
                    <label for="unitPrice" class="form-label">Harga Satuan</label>
                    <input type="number" class="form-control" id="unitPrice" placeholder="Rp.99.000">
                </div>
                        
                        <div class="mb-3">
                          <label class="form-label">Diskon</label>
                            <div class="input-group">
                              <input type="number" class="form-control" id="discountValue" placeholder="Masukkan diskon" aria-label="Diskon" style="width: 70%;">
                              <select class="form-select" id="discountType">
                                  <option value="percentage">%</option>
                                  <option value="rupiah">Rp</option>
                              </select>
                          </div>
                        </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
  @endforeach
  @else

  <div class="alert alert-primary">
    <p><i class='bx bx-info-circle me-2'></i> <span class="text-primary">Petunjuk</span></p>
    <ul class="mb-0" style="list-style: none; padding-left: 0;">
      <li style="display: flex; align-items: start; gap: 8px; margin-bottom: 8px;">
        <i class='bx bxs-category'></i>
        <span class="text-primary">Mulai dengan memilih toko. Klik tombol <i class='bx bxs-category'></i> di area keranjang.</span>
      </li>
      <li style="display: flex; align-items: start; gap: 8px; margin-bottom: 8px;">
        <i class='bx bx-barcode-reader'></i>
        <span class="text-primary">Tambahkan produk ke keranjang melalui fitur pencarian atau scan barcode (untuk versi mobile).</span>
      </li>
      <li style="display: flex; align-items: start; gap: 8px; margin-bottom: 8px;">
        <i class='bx bx-user'></i>
        <span class="text-primary">Klik nama atau <i class='bx bx-user'></i> untuk mengganti nama pelanggan.</span>
      </li>
      <li style="display: flex; align-items: start; gap: 8px;">
        <i class='bx bx-trash'></i>
        <span class="text-primary">Klik <i class='bx bx-trash'></i> untuk menghapus semua barang yang dipilih.</span>
      </li>
    </ul>
  </div>

  @endif
</div>
<script>
  function removeFromCart(rowId) {
    fetch(`{{ route('remove-from-cart', '') }}/${rowId}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          document.dispatchEvent(new Event('productAdded'));
        } else {
          alert('Gagal menghapus produk dari keranjang');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus produk dari keranjang');
      });
  }

  document.addEventListener('DOMContentLoaded', function() {
    const cartItems = document.getElementById('cartItems');

    cartItems.addEventListener('click', function(event) {
      const target = event.target;
      if (target.classList.contains('btn-plus') || target.classList.contains('btn-minus')) {
        const input = target.closest('.input-group').querySelector('.qty-input');
        const rowId = input.dataset.rowid;
        const price = parseFloat(input.dataset.price);
        const stock = parseInt(input.dataset.stock);
        let newQty = parseInt(input.value);

        if (target.classList.contains('btn-plus')) {
          newQty = Math.min(newQty + 1, stock); // Menambahkan qty dengan batasan stock
        } else if (target.classList.contains('btn-minus')) {
          newQty = Math.max(newQty - 1, 1); // Mengurangi qty dengan batasan minimal 1
        }

        input.value = newQty;

        const subtotal = price * newQty;
        const subtotalElement = input.closest('.item-details').querySelector('.item-subtotal');
        subtotalElement.innerHTML = `Rp ${subtotal.toLocaleString('id-ID')}`;
        updateCart(rowId, newQty);
      }
    });

    cartItems.addEventListener('change', function(event) {
      if (event.target.classList.contains('qty-input')) {
        const input = event.target;
        const rowId = input.dataset.rowid;
        const price = parseFloat(input.dataset.price);
        const stock = parseInt(input.dataset.stock);
        let newQty = parseInt(input.value);

        if (newQty > stock) {
          newQty = stock;
          input.value = stock;
          alert('Qty melebhi stok yang tersedia');
        }

        const subtotal = price * newQty;

        const subtotalElement = input.closest('.item-details').querySelector('.item-subtotal');
        subtotalElement.textContent = subtotal.toLocaleString('id-ID');

        updateCart(rowId, newQty);
      }
    });
  });

  function updateCart(rowId, qty) {
    fetch(`{{ route('update-cart', '') }}/${rowId}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          qty: qty
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          document.dispatchEvent(new Event('productAdded'));
        } else {
          alert('Gagal memperbarui keranjang');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memperbarui keranjang');
      });
  }
</script>