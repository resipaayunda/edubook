<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sidebar-blue-vibrant" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav custom-nav-spacing">

                    <div class="menu-label">MENU</div>

                    <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active-item' : '' }}" href="{{ route('user.dashboard') }}">
                        <div class="nav-icon"><i class="fas fa-th-large"></i></div>
                        <span class="nav-text">Dashboard</span>
                    </a>

                    <a class="nav-link {{ request()->routeIs('user.buku.*') ? 'active-item' : '' }}" href="{{ route('user.buku.index') }}">
                        <div class="nav-icon"><i class="fas fa-chart-bar"></i></div>
                        <span class="nav-text">Daftar Buku</span>
                    </a>

                    <a class="nav-link {{ request()->routeIs('user.pengembalian.*') ? 'active-item' : '' }}" href="{{ route('user.pengembalian.index') }}">
                        <div class="nav-icon"><i class="fas fa-history"></i></div>
                        <span class="nav-text">Pengembalian</span>
                    </a>

                </div>
            </div>
        </nav>
    </div>
</div>

<style>
    /* Sidebar Utama - Biru Terang & Fresh */
    .sidebar-blue-vibrant {
        background: linear-gradient(135deg, #0052D4 0%, #4364F7 50%, #6FB1FC 100%);
        padding: 10px 12px;
        min-height: 100vh;
        box-shadow: 4px 0 15px rgba(0, 82, 212, 0.2);
    }

    /* Memberikan jarak atas pada navigasi agar menu lebih turun */
    .custom-nav-spacing {
        padding-top: 40px; 
    }

    /* Label Group Menu */
    .menu-label {
        color: rgba(255, 255, 255, 0.7);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 2px;
        margin-left: 15px;
        margin-bottom: 20px;
    }

    /* Link Default */
    .nav-link {
        color: rgba(255, 255, 255, 0.75) !important;
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 12px; /* Jarak antar tiap menu fitur */
        display: flex;
        align-items: center;
        text-decoration: none !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 500;
        border-left: 4px solid transparent;
    }

    /* ----- EFEK HOVER ----- */
    .nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff !important;
    }

    .nav-link:hover .nav-text {
        transform: translateX(8px);
        transition: 0.3s;
    }

    /* ----- EFEK SAAT DIPENCET (AKTIF) ----- */
    /* Menggunakan glassmorphism, tidak putih polos */
    .active-item {
        background: rgba(255, 255, 255, 0.25) !important; 
        color: #ffffff !important;
        font-weight: 700;
        border-left: 4px solid #ffffff; 
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .active-item .nav-icon {
        color: #ffffff !important;
        transform: scale(1.1);
    }

    /* Icon Styling */
    .nav-icon {
        width: 30px;
        font-size: 18px;
        display: flex;
        justify-content: center;
        margin-right: 12px;
        transition: 0.3s;
    }

    .nav-link:hover .nav-icon {
        transform: scale(1.2);
    }
</style>