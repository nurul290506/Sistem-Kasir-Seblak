@extends('layouts.app')

@section('page_title', 'Kasir')

@section('content')
<div class="pos-container">
    <!-- Left Column: Search & Item Catalog -->
    <div class="pos-catalog-column">
        <!-- Search and Catalog Filter -->
        <div class="d-flex align-items-center justify-content-between mb-4 gap-3">
            <div class="input-group" style="width: 300px; flex-shrink: 0;">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" id="search-input" class="form-control border-start-0" placeholder="Cari topping atau minuman...">
            </div>
            
            <div class="d-flex gap-2 overflow-auto ms-auto" style="white-space: nowrap; padding-bottom: 4px;">
                <button class="btn-cat-filter active" data-category="all">Semua Menu</button>
                <button class="btn-cat-filter" data-category="Topping">Topping & Seblak</button>
                <button class="btn-cat-filter" data-category="Cemilan">Cemilan</button>
                <button class="btn-cat-filter" data-category="Minuman">Minuman</button>
            </div>
        </div>

        <!-- Items Grid -->
        <div class="pos-items-grid" id="catalog-grid">
            @foreach($items as $item)
                <div class="pos-item-card" 
                     data-id="{{ $item->id_barang }}" 
                     data-nama="{{ $item->nama_barang }}" 
                     data-price="{{ $item->harga_jual }}" 
                     data-category="{{ $item->kategori->nama_kategori }}" 
                     data-satuan="{{ $item->satuan }}" 
                     data-stok="{{ $item->stok }}">
                    
                    <span class="pos-item-badge">{{ $item->kategori->nama_kategori }}</span>
                    <div class="pos-item-name">{{ $item->nama_barang }}</div>
                    <div class="pos-item-price">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</div>
                    <div class="pos-item-stock {{ $item->stok < 15 ? 'low' : '' }}">
                        Stok: {{ $item->stok }} {{ $item->satuan }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Right Column: Cart & Checkout -->
    <div class="pos-cart-column">
        <div class="cart-header">
            <h5 class="m-0"><i class="fa-solid fa-cart-shopping text-primary me-2"></i> Keranjang Belanja</h5>
            <button class="btn btn-sm btn-outline-danger" id="clear-cart-btn"><i class="fa-solid fa-trash-can"></i> Bersihkan</button>
        </div>

        <!-- Cart Items List -->
        <div class="cart-items-wrapper" id="cart-items-list">
            <!-- Empty State -->
            <div class="cart-empty-state" id="cart-empty-state">
                <i class="fa-solid fa-basket-shopping"></i>
                <p class="m-0">Keranjang masih kosong</p>
                <small class="text-muted">Klik menu di sebelah kiri untuk menambahkan</small>
            </div>
            <!-- Dynamic rows will go here -->
        </div>

        <!-- Cart Checkout Summary -->
        <div class="cart-footer">
            <div class="cart-summary-line">
                <span>Total Item:</span>
                <span class="fw-bold" id="total-qty-display">0</span>
            </div>
            <div class="cart-summary-line total">
                <span>Total Bayar:</span>
                <span id="total-price-display">Rp 0</span>
            </div>

            <!-- Level Pedas Keseluruhan -->
            <div class="mb-3">
                <label class="form-label fw-bold small text-muted text-uppercase">Level Pedas</label>
                <div class="d-flex gap-2 flex-wrap">
                    <input type="radio" class="btn-check" name="global_level_pedas" id="lvl-0" value="0" checked>
                    <label class="btn btn-outline-danger py-1 px-2 flex-grow-1" for="lvl-0">Lvl 0</label>
                    
                    <input type="radio" class="btn-check" name="global_level_pedas" id="lvl-1" value="1">
                    <label class="btn btn-outline-danger py-1 px-2 flex-grow-1" for="lvl-1">1</label>
                    
                    <input type="radio" class="btn-check" name="global_level_pedas" id="lvl-2" value="2">
                    <label class="btn btn-outline-danger py-1 px-2 flex-grow-1" for="lvl-2">2</label>
                    
                    <input type="radio" class="btn-check" name="global_level_pedas" id="lvl-3" value="3">
                    <label class="btn btn-outline-danger py-1 px-2 flex-grow-1" for="lvl-3">3</label>
                    
                    <input type="radio" class="btn-check" name="global_level_pedas" id="lvl-4" value="4">
                    <label class="btn btn-outline-danger py-1 px-2 flex-grow-1" for="lvl-4">4</label>
                    
                    <input type="radio" class="btn-check" name="global_level_pedas" id="lvl-5" value="5">
                    <label class="btn btn-outline-danger py-1 px-2 flex-grow-1" for="lvl-5">5</label>
                </div>
            </div>

            <!-- Payment Details Form -->
            <div class="mb-3">
                <label class="form-label fw-bold small text-muted text-uppercase">Metode Pembayaran</label>
                <div class="btn-group w-100" role="group">
                    <input type="radio" class="btn-check" name="metode_pembayaran" id="pay-tunai" value="tunai" checked>
                    <label class="btn btn-outline-secondary py-2" for="pay-tunai"><i class="fa-solid fa-money-bill-wave me-1"></i> Tunai</label>

                    <input type="radio" class="btn-check" name="metode_pembayaran" id="pay-transfer" value="transfer">
                    <label class="btn btn-outline-secondary py-2" for="pay-transfer"><i class="fa-solid fa-building-columns me-1"></i> Transfer</label>

                    <input type="radio" class="btn-check" name="metode_pembayaran" id="pay-qris" value="qris">
                    <label class="btn btn-outline-secondary py-2" for="pay-qris"><i class="fa-solid fa-qrcode me-1"></i> QRIS</label>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label for="input-bayar" class="form-label fw-bold small text-muted text-uppercase">Bayar (Rp)</label>
                    <input type="number" id="input-bayar" class="form-control form-control-lg fw-bold" placeholder="0" min="0">
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold small text-muted text-uppercase">Kembalian</label>
                    <div class="fs-4 fw-extrabold text-success mt-1" id="change-display">Rp 0</div>
                </div>
            </div>


            <button class="btn btn-primary-custom w-100 py-3 fs-5" id="checkout-btn" disabled>
                <i class="fa-solid fa-file-invoice-dollar me-2"></i> Proses Pembayaran
            </button>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="border-radius: var(--border-radius);">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel"><i class="fa-solid fa-circle-check text-success me-2"></i> Transaksi Sukses</h5>
            </div>
            <div class="modal-body text-center">
                <i class="fa-solid fa-receipt text-primary mb-3" style="font-size: 48px;"></i>
                <p class="mb-4">Apakah Anda ingin mencetak struk belanja transaksi ini?</p>
                <div class="d-grid gap-2">
                    <a href="#" id="print-receipt-link" target="_blank" class="btn btn-primary-custom">
                        <i class="fa-solid fa-print me-1"></i> Cetak Struk
                    </a>
                    <button type="button" class="btn btn-secondary-custom" id="close-modal-btn">
                        Transaksi Baru
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const catalogCards = document.querySelectorAll('.pos-item-card');
        const searchInput = document.getElementById('search-input');
        const catFilters = document.querySelectorAll('.btn-cat-filter');
        const cartWrapper = document.getElementById('cart-items-list');
        const cartEmptyState = document.getElementById('cart-empty-state');
        const totalQtyDisplay = document.getElementById('total-qty-display');
        const totalPriceDisplay = document.getElementById('total-price-display');
        const inputBayar = document.getElementById('input-bayar');
        const changeDisplay = document.getElementById('change-display');
        const checkoutBtn = document.getElementById('checkout-btn');
        const clearCartBtn = document.getElementById('clear-cart-btn');

        const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
        const printReceiptLink = document.getElementById('print-receipt-link');
        const closeModalBtn = document.getElementById('close-modal-btn');

        let cart = [];

        // 1. Search filter
        searchInput.addEventListener('input', filterCatalog);

        // 2. Category filter
        catFilters.forEach(btn => {
            btn.addEventListener('click', function() {
                catFilters.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                filterCatalog();
            });
        });

        function filterCatalog() {
            const query = searchInput.value.toLowerCase();
            const activeCategory = document.querySelector('.btn-cat-filter.active').getAttribute('data-category');

            catalogCards.forEach(card => {
                const name = card.getAttribute('data-nama').toLowerCase();
                const category = card.getAttribute('data-category');
                
                const matchesSearch = name.includes(query);
                const matchesCategory = activeCategory === 'all' || category === activeCategory;

                if (matchesSearch && matchesCategory) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // 3. Add to cart
        catalogCards.forEach(card => {
            card.addEventListener('click', function() {
                const id = parseInt(this.getAttribute('data-id'));
                const name = this.getAttribute('data-nama');
                const price = parseFloat(this.getAttribute('data-price'));
                const stock = parseInt(this.getAttribute('data-stok'));
                const category = this.getAttribute('data-category');
                const satuan = this.getAttribute('data-satuan');

                if (stock <= 0) {
                    alert('Stok barang habis!');
                    return;
                }

                addToCart(id, name, price, stock, category, satuan);
            });
        });

        function addToCart(id, name, price, stock, category, satuan) {
            const existingIndex = cart.findIndex(item => item.id === id);

            if (existingIndex > -1) {
                if (cart[existingIndex].qty >= stock) {
                    alert('Tidak bisa menambah item lagi. Batas stok tercapai!');
                    return;
                }
                cart[existingIndex].qty++;
            } else {
                cart.push({
                    id: id,
                    name: name,
                    price: price,
                    qty: 1,
                    stock: stock,
                    category: category,
                    satuan: satuan,
                    level_pedas: category === 'Topping' ? 0 : null // Toppings default level 0
                });
            }

            renderCart();
        }

        // 4. Render Cart
        function renderCart() {
            // Remove previous item DOMs
            const items = cartWrapper.querySelectorAll('.cart-item');
            items.forEach(el => el.remove());

            if (cart.length === 0) {
                cartEmptyState.style.display = 'flex';
                clearCartBtn.style.disabled = true;
                checkoutBtn.disabled = true;
                totalQtyDisplay.innerText = 0;
                totalPriceDisplay.innerText = 'Rp 0';
                inputBayar.value = '';
                changeDisplay.innerText = 'Rp 0';
                return;
            }

            cartEmptyState.style.display = 'none';
            clearCartBtn.style.disabled = false;

            let totalQty = 0;
            let totalPrice = 0;

            cart.forEach((item, index) => {
                totalQty += item.qty;
                const subtotal = item.qty * item.price;
                totalPrice += subtotal;

                const tr = document.createElement('div');
                tr.className = 'cart-item';
                tr.innerHTML = `
                    <div class="cart-item-info">
                        <h6>${item.name}</h6>
                        <span>Rp ${item.price.toLocaleString('id-ID')}</span>

                    </div>
                    <div class="cart-item-actions">
                        <div class="cart-item-qty">
                            <span class="btn-qty btn-qty-minus" data-idx="${index}"><i class="fa-solid fa-minus"></i></span>
                            <span class="qty-val">${item.qty}</span>
                            <span class="btn-qty btn-qty-plus" data-idx="${index}"><i class="fa-solid fa-plus"></i></span>
                        </div>
                        <div class="cart-item-subtotal mt-2">Rp ${subtotal.toLocaleString('id-ID')}</div>
                        <button class="cart-item-remove mt-1" data-idx="${index}"><i class="fa-solid fa-trash me-1"></i>Hapus</button>
                    </div>
                `;

                cartWrapper.appendChild(tr);
            });

            totalQtyDisplay.innerText = totalQty;
            totalPriceDisplay.innerText = 'Rp ' + totalPrice.toLocaleString('id-ID');

            // Recalculate change
            calculateChange(totalPrice);
            checkoutBtn.disabled = false;

            // Bind listeners
            bindCartListeners();
        }

        function bindCartListeners() {
            // Plus buttons
            cartWrapper.querySelectorAll('.btn-qty-plus').forEach(btn => {
                btn.addEventListener('click', function() {
                    const idx = parseInt(this.getAttribute('data-idx'));
                    const item = cart[idx];
                    if (item.qty >= item.stock) {
                        alert('Batas stok tercapai!');
                        return;
                    }
                    item.qty++;
                    renderCart();
                });
            });

            // Minus buttons
            cartWrapper.querySelectorAll('.btn-qty-minus').forEach(btn => {
                btn.addEventListener('click', function() {
                    const idx = parseInt(this.getAttribute('data-idx'));
                    const item = cart[idx];
                    if (item.qty > 1) {
                        item.qty--;
                    } else {
                        cart.splice(idx, 1);
                    }
                    renderCart();
                });
            });

            // Remove buttons
            cartWrapper.querySelectorAll('.cart-item-remove').forEach(btn => {
                btn.addEventListener('click', function() {
                    const idx = parseInt(this.getAttribute('data-idx'));
                    cart.splice(idx, 1);
                    renderCart();
                });
            });


        }

        // 5. Change Calculation
        inputBayar.addEventListener('input', function() {
            const totalPrice = getCartTotalPrice();
            calculateChange(totalPrice);
        });

        function getCartTotalPrice() {
            return cart.reduce((sum, item) => sum + (item.qty * item.price), 0);
        }

        function calculateChange(totalPrice) {
            const payAmount = parseFloat(inputBayar.value) || 0;
            const change = payAmount - totalPrice;

            if (payAmount >= totalPrice && totalPrice > 0) {
                changeDisplay.innerText = 'Rp ' + change.toLocaleString('id-ID');
                changeDisplay.className = 'fs-4 fw-extrabold text-success mt-1';
                checkoutBtn.disabled = false;
            } else {
                changeDisplay.innerText = 'Rp 0';
                changeDisplay.className = 'fs-4 fw-extrabold text-danger mt-1';
                checkoutBtn.disabled = true;
            }
        }


        // Clear cart
        clearCartBtn.addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin mengosongkan keranjang?')) {
                cart = [];
                renderCart();
            }
        });

        // 6. Checkout Process via AJAX
        checkoutBtn.addEventListener('click', function() {
            const totalPrice = getCartTotalPrice();
            const payAmount = parseFloat(inputBayar.value) || 0;
            const method = document.querySelector('input[name="metode_pembayaran"]:checked').value;
            const globalLevel = parseInt(document.querySelector('input[name="global_level_pedas"]:checked').value);

            if (payAmount < totalPrice) {
                alert('Uang bayar tidak cukup!');
                return;
            }

            checkoutBtn.disabled = true;
            checkoutBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Memproses...';

            const payload = {
                metode_pembayaran: method,
                bayar: payAmount,
                total_harga: totalPrice,
                items: cart.map(item => ({
                    id_barang: item.id,
                    jumlah: item.qty,
                    level_pedas: globalLevel
                }))
            };

            // Post request to Laravel
            fetch("{{ route('transaksi.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(res => {
                if (res.status === 200) {
                    // Success! Show print receipt modal
                    printReceiptLink.href = `/transaksi/${res.body.id_transaksi}/print`;
                    receiptModal.show();
                } else {
                    alert(res.body.error || 'Terjadi kesalahan sistem.');
                    resetCheckoutButton();
                }
            })
            .catch(err => {
                console.error(err);
                alert('Gagal memproses transaksi. Cek koneksi Anda.');
                resetCheckoutButton();
            });
        });

        function resetCheckoutButton() {
            checkoutBtn.disabled = false;
            checkoutBtn.innerHTML = '<i class="fa-solid fa-file-invoice-dollar me-2"></i> Proses Pembayaran';
        }

        // Close modal and reload page/clear cart
        closeModalBtn.addEventListener('click', function() {
            receiptModal.hide();
            // Clear cart & variables
            cart = [];
            renderCart();
            resetCheckoutButton();
            // Reload window to update stock in catalog UI
            window.location.reload();
        });
    });
</script>
@endsection
