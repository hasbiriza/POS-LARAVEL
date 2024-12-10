    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4 class="text-primary mb-4 border-bottom pb-2"><i class="bx bx-info-circle me-2"></i>Informasi Umum</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <h6 class="text-muted"><i class="bx bx-package me-2"></i>Nama Produk</h6>
                        <p class="fs-5 fw-bold">{{ $product->name }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted"><i class="bx bx-purchase-tag me-2"></i>Merek</h6>
                        <p class="fs-5">{{ $product->brand->name }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted"><i class="bx bx-cube me-2"></i>Unit</h6>
                        <p class="fs-5">{{ $product->unit->name }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <h6 class="text-muted"><i class="bx bx-text me-2"></i>Deskripsi</h6>
                        <p class="fs-5">{{ $product->description }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted"><i class="bx bx-category me-2"></i>Kategori</h6>
                        <p>
                            @foreach($product->categories as $category)
                            <span class="badge bg-info me-1 fs-6">{{ $category->name }}</span>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Variasi -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4 class="text-primary mb-4 border-bottom pb-2"><i class="bx bx-list-ul me-2"></i>Detail Variasi</h4>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="fs-6">Varian</th>
                            <th class="fs-6">Barcode</th>
                            <th class="fs-6">SKU</th>
                            <th class="fs-6">Harga Beli</th>
                            <th class="fs-6">Harga Jual</th>
                            <th class="fs-6">Margin (%)</th>
                            <th class="fs-6">Berat</th>
                            <th class="fs-6">Gambar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->variants as $variant)
                        <tr>
                            <td class="fs-6 fw-bold">
                                {{ $variant->variasi_1 ?: '-' }}
                                {{ $variant->variasi_2 ? ' - ' . $variant->variasi_2 : '' }}
                                {{ $variant->variasi_3 ? ' - ' . $variant->variasi_3 : '' }}
                            </td>
                            <td class="fs-6">{{ $variant->barcode }}</td>
                            <td class="fs-6">{{ $variant->sku }}</td>
                            <td>Rp {{ number_format($variant->purchase_price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($variant->sale_price, 0, ',', '.') }}</td>
                            <td>
                                @if(is_numeric($variant->purchase_price) && $variant->purchase_price > 0)
                                {{ round((($variant->sale_price - $variant->purchase_price) / $variant->purchase_price) * 100, 2) }}%
                                @else
                                -
                                @endif
                            </td>
                            <td class="fs-6">{{ $variant->weight }} kg</td>
                            <td>
                                <div class="row g-2">
                                    @foreach($variant->images as $image)
                                    <div class="col-4">
                                        <img src="{{ asset('storage/' . $image->image_url) }}" class="img-thumbnail" alt="Gambar Produk" style="width: 100%; height: 80px; object-fit: cover;">
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Detail Produk -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="text-primary mb-4 border-bottom pb-2"><i class="bx bx-list-ul me-2"></i>Detail Produk</h4>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="fs-6">Varian</th>
                            <th class="fs-6">Barcode</th>
                            <th class="fs-6">SKU</th>
                            <th class="fs-6">Harga Jual</th>
                            <th class="fs-6">Stok</th>
                            <th class="fs-6">Nilai Stok</th>
                            <th class="fs-6">Total Stok Terjual</th>
                            <th class="fs-6">Toko</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->variants as $variant)
                        <tr>
                            <td class="fs-6 fw-bold">
                                {{ $variant->variasi_1 ?: '-' }}
                                {{ $variant->variasi_2 ? ' - ' . $variant->variasi_2 : '' }}
                                {{ $variant->variasi_3 ? ' - ' . $variant->variasi_3 : '' }}
                            </td>
                            <td class="fs-6">{{ $variant->barcode }}</td>
                            <td class="fs-6">{{ $variant->sku }}</td>
                            <td>Rp {{ number_format($variant->sale_price, 0, ',', '.') }}</td>
                            <td><span class="badge bg-label-primary fs-6">{{ $variant->stock }}</span></td>
                            <td class="fs-6">Rp {{ number_format($variant->sale_price * $variant->stock, 0, ',', '.') }}</td>
                            <td class="fs-6">Ini Belum contoh (155pcs)</td>
                            <td class="fs-6">{{ $variant->store->store_name }}</td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>