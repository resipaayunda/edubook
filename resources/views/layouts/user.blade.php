<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>EduBook</title>

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
    
    {{-- CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('admin_template/startbootstrap-sb-admin-gh-pages/css/styles.css') }}" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Navbar Senada dengan Sidebar */
        .navbar-custom {
            background: linear-gradient(to right, #0052D4, #4364F7);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 10px 0;
        }

        /* Judul EduBook di Sidebar */
        .navbar-brand {
            font-size: 22px !important;
            font-weight: 800 !important;
            letter-spacing: -0.5px;
            width: 225px; /* Menyesuaikan lebar sidebar agar teks terlihat di tengah */
            text-align: center;
            margin-right: 0 !important;
            padding-left: 0 !important;
            color: white !important;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Tombol Toggle */
        #sidebarToggle {
            color: white !important;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            margin-left: 10px;
            transition: 0.3s;
        }

        #sidebarToggle:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Badge Role */
        .role-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Dropdown User */
        .dropdown-menu-custom {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 12px;
            margin-top: 15px !important;
            padding: 10px;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 10px 15px;
            color: #1e293b;
            font-weight: 500;
            transition: 0.2s;
        }

        .dropdown-item:hover {
            background-color: #f1f5f9;
            color: #0052D4;
        }

        /* Garis pemisah konten agar bersih */
        main {
            background-color: #f8fafc; /* Abu-abu sangat muda biar dashboardnya clean */
            min-height: 100vh;
        }
    </style>
</head>

<body class="sb-nav-fixed">

    {{-- TOP NAVBAR --}}
    <nav class="sb-topnav navbar navbar-expand navbar-dark navbar-custom">
        <a class="navbar-brand fw-bold" href="#">EduBook</a>

        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle">
            <i class="fas fa-bars-staggered"></i>
        </button>

        <ul class="navbar-nav ms-auto me-3 align-items-center">

            {{-- ROLE BADGE --}}
            <li class="nav-item text-white me-3 d-none d-md-block">
                <span class="role-badge">
                    {{ Auth::check() ? Auth::user()->role : 'Guest' }}
                </span>
            </li>

            {{-- USER DROPDOWN --}}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <div style="width: 35px; height: 35px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user"></i>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                    <li class="px-3 py-2 border-bottom mb-2">
                        <div class="small text-muted">Signed in as:</div>
                        <div class="fw-bold">{{ Auth::user()->name ?? 'User' }}</div>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}"> 
                            @csrf
                            <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    {{-- LAYOUT --}}
    <div id="layoutSidenav">

        {{-- SIDEBAR --}}
        @include('partials.sidebar-user')

        {{-- CONTENT --}}
        <div id="layoutSidenav_content">
            <main class="p-4">
                <div class="container-fluid px-4">
                    @yield('content')
                </div>
            </main>
        </div>

    </div>

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('admin_template/startbootstrap-sb-admin-gh-pages/js/scripts.js') }}"></script>

</body>
</html>