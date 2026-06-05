<!doctype html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ghina Tour Travel - Paket Wisata Purwokerto')</title>
    <meta name="description" content="@yield(
        'description',
        'Ghina Tour Travel menyediakan paket wisata, open trip, dan sewa bus        
      pariwisata dari Purwokerto.'
    )" />
    <link rel="canonical" href="@yield('canonical', url()->current())" />

    <meta property="og:title" content="@yield('title', 'Ghina Tour Travel')" />
    <meta property="og:description" content="@yield('description', 'Paket wisata terpercaya dari Purwokerto.')" />
    <meta property="og:url" content="@yield('canonical', url()->current())" />
    <meta property="og:type" content="@yield('og_type', 'website')" />
    <meta property="og:image" content="@yield('og_image', asset('customer/assets/images/logos/logo.png'))" />

    <meta name="twitter:card" content="summary_large_image" />
    <script>
        (function() {
            const isDark = localStorage.getItem('theme') === 'dark';
            document.documentElement.classList.toggle('dark', isDark);
            document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/css/customer.css', 'resources/css/chatbot.css', 'resources/js/app.js'])

    @yield('extra_styles')
</head>

<body class="overflow-x-hidden">

    @include('components.layout.navbar')

    @yield('content')

    @include('components.layout.footer')

    <x-chatbot-widget mode="public" />


    @include('components.customer.lightbox')

    @include('components.layout.scripts')
    @yield('extra_scripts')
</body>

</html>
