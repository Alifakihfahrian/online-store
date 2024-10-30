<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Customer Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('extra_css')
    <style>
        /* Warna dasar untuk mode light */
        [data-bs-theme="light"] .navbar {
            --navbar-color: #212529;
        }

        /* Warna dasar untuk mode dark */
        [data-bs-theme="dark"] .navbar {
            --navbar-color: #f8f9fa;
        }

        /* Styling navbar */
        .navbar-nav .nav-link,
        .navbar-brand,
        #themeSwitcher {
            color: var(--navbar-color);
        }

        /* Hapus efek hover */
        .navbar-nav .nav-link:hover,
        .navbar-brand:hover {
            color: var(--navbar-color);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg" data-bs-theme="light" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('customer.dashboard') }}">Toko Online</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                            Layanan
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('pulsa.index') }}">Beli Pulsa</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('cart.index') }}" class="nav-link">
                            <i class="bi bi-cart"></i>
                            <span class="badge bg-danger rounded-pill">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <button class="btn nav-link" id="themeSwitcher">
                            <i class="bi bi-sun-fill theme-icon-active"></i>
                        </button>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="btn nav-link" onclick="logout()">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <script>
        // Theme switcher
        document.addEventListener('DOMContentLoaded', function() {
            const themeSwitcher = document.getElementById('themeSwitcher');
            const mainNavbar = document.getElementById('mainNavbar');
            const html = document.documentElement;
            
            // Check saved theme
            const savedTheme = localStorage.getItem('theme') || 
                (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            
            setTheme(savedTheme);

            themeSwitcher.addEventListener('click', () => {
                const currentTheme = html.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                setTheme(newTheme);
            });

            function setTheme(theme) {
                html.setAttribute('data-bs-theme', theme);
                mainNavbar.setAttribute('data-bs-theme', theme);
                localStorage.setItem('theme', theme);

                // Update icon
                const themeIcon = themeSwitcher.querySelector('.theme-icon-active');
                themeIcon.classList.remove('bi-sun-fill', 'bi-moon-fill');
                themeIcon.classList.add(theme === 'dark' ? 'bi-moon-fill' : 'bi-sun-fill');
            }
        });

        // Update cart count on page load (only if not on cart page)
        @if(Route::currentRouteName() != 'cart.view')
        $.get('/cart/count', function(data) {
            $('#cart-count').text(data);
        });
        @endif

        document.addEventListener('DOMContentLoaded', function() {
            const themeSwitcher = document.getElementById('themeSwitcher');
            const mainNavbar = document.getElementById('mainNavbar');

            themeSwitcher.addEventListener('click', function() {
                if (mainNavbar.getAttribute('data-bs-theme') === 'dark') {
                    mainNavbar.setAttribute('data-bs-theme', 'light');
                    themeSwitcher.innerHTML = '<i class="bi bi-sun-fill"></i> Light Mode';
                } else {
                    mainNavbar.setAttribute('data-bs-theme', 'dark');
                    themeSwitcher.innerHTML = '<i class="bi bi-moon-fill"></i> Dark Mode';
                }
            });
        });

        function logout() {
            Swal.fire({
                title: 'Logout',
                text: "Apakah anda yakin ingin keluar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('login') }}";
                }
            });
        }
    </script>
    @yield('extra_js')
</body>
</html>
