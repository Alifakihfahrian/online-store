<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Authentication')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            min-height: 100vh;
        }
        .theme-switch {
            position: fixed;
            top: 1rem;
            right: 1rem;
        }
    </style>
    @yield('extra_css')
</head>
<body>
    <div class="theme-switch">
        <button class="btn btn-outline-secondary" id="themeSwitcher">
            <i class="bi bi-sun-fill"></i> Light Mode
        </button>
    </div>

    <div class="container">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
    </script>
    @yield('extra_js')
</body>
</html>
