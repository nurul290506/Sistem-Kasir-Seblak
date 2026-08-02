<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Seblak Bundaka - POS & Stock Management</title>
    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles')
</head>
<body>
    <div class="app-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo-wrapper">
                    <span class="fire-icon"><i class="fa-solid fa-fire"></i></span>
                    <div class="logo-text">
                        <h3>Seblak</h3>
                        <span>Bundaka</span>
                    </div>
                </div>
            </div>
            <nav class="sidebar-menu">
                <ul>
                    <li class="{{ Route::is('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}">
                            <i class="fa-solid fa-chart-pie"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="{{ Route::is('transaksi.index') ? 'active' : '' }}">
                        <a href="{{ route('transaksi.index') }}">
                            <i class="fa-solid fa-cash-register"></i>
                            <span>Kasir POS</span>
                        </a>
                    </li>
                    <li class="{{ Route::is('transaksi.history') || Route::is('transaksi.show') ? 'active' : '' }}">
                        <a href="{{ route('transaksi.history') }}">
                            <i class="fa-solid fa-receipt"></i>
                            <span>Riwayat Transaksi</span>
                        </a>
                    </li>

                    @if(auth()->user()->role === 'admin')
                        <li class="menu-divider"><span>Master Data</span></li>
                        <li class="{{ Route::is('barang.index') ? 'active' : '' }}">
                            <a href="{{ route('barang.index') }}">
                                <i class="fa-solid fa-boxes-stacked"></i>
                                <span>Stok & Menu</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('kategori.index') ? 'active' : '' }}">
                            <a href="{{ route('kategori.index') }}">
                                <i class="fa-solid fa-tags"></i>
                                <span>Kategori Barang</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('supplier.index') ? 'active' : '' }}">
                            <a href="{{ route('supplier.index') }}">
                                <i class="fa-solid fa-truck-ramp-box"></i>
                                <span>Supplier</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('users.index') ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}">
                                <i class="fa-solid fa-users-gear"></i>
                                <span>Kelola Pengguna</span>
                            </a>
                        </li>

                        <li class="menu-divider"><span>Inventori & Keuangan</span></li>
                        <li class="{{ Route::is('pembelian.*') ? 'active' : '' }}">
                            <a href="{{ route('pembelian.index') }}">
                                <i class="fa-solid fa-cart-flatbed-suitcase"></i>
                                <span>Restok / Pembelian</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('reports.index') ? 'active' : '' }}">
                            <a href="{{ route('reports.index') }}">
                                <i class="fa-solid fa-chart-line"></i>
                                <span>Laporan Penjualan</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
            <div class="sidebar-footer">
                <div class="user-profile">
                    <div class="user-avatar">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div class="user-info">
                        <h5>{{ auth()->user()->nama_user }}</h5>
                        <span>{{ ucfirst(auth()->user()->role) }}</span>
                    </div>
                </div>
                <a href="{{ route('logout') }}" class="btn-logout" title="Logout"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa-solid fa-power-off"></i>
                </a>
                <form id="logout-form" action="{{ route('logout.post') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Navbar -->
            <header class="navbar-custom">
                <div class="nav-left">
                    <button class="btn-toggle-sidebar d-md-none">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <h4 class="page-title">@yield('page_title', 'Sistem Kasir Seblak Bundaka')</h4>
                </div>
                <div class="nav-right">
                    <div class="date-badge">
                        <i class="fa-regular fa-calendar-days"></i>
                        <span id="current-date-time">Sabtu, 01 Agustus 2026</span>
                    </div>
                </div>
            </header>

            <!-- Inner Content -->
            <div class="content-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data- Mull-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-xmark me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS Helper -->
    <script>
        // Update date/time dynamically
        function updateDateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            document.getElementById('current-date-time').innerText = now.toLocaleDateString('id-ID', options);
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();

        // Sidebar responsive toggle
        const toggleBtn = document.querySelector('.btn-toggle-sidebar');
        const sidebar = document.querySelector('.sidebar');
        if(toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
