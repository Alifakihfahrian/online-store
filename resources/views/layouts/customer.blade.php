<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Customer Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('extra_css')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('customer.dashboard') }}">Toko Online</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="{{ route('cart.index') }}" class="nav-link position-relative">
                            <i class="bi bi-cart"></i> Keranjang
                            <span id="cart-count" class="badge bg-secondary">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-link nav-link" id="themeSwitcher">
                            <i class="bi bi-sun-fill"></i> Light Mode
                        </button>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Theme switcher
        const themeSwitcher = document.getElementById('themeSwitcher');
        const htmlElement = document.documentElement;
        
        function setTheme(theme) {
            htmlElement.setAttribute('data-bs-theme', theme);
            localStorage.setItem('theme', theme);
            updateThemeSwitcherText(theme);
        }

        function updateThemeSwitcherText(theme) {
            if (theme === 'dark') {
                themeSwitcher.innerHTML = '<i class="bi bi-moon-fill"></i> Dark Mode';
            } else {
                themeSwitcher.innerHTML = '<i class="bi bi-sun-fill"></i> Light Mode';
            }
        }

        themeSwitcher.addEventListener('click', () => {
            const currentTheme = htmlElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            setTheme(newTheme);
        });

        // Set initial theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        setTheme(savedTheme);

        // Update cart count on page load (only if not on cart page)
        @if(Route::currentRouteName() != 'cart.view')
        $.get('/cart/count', function(data) {
            $('#cart-count').text(data);
        });
        @endif
    </script>
    @yield('extra_js')
</body>
</html>
