<link rel="stylesheet" href="{{ asset('css/app.css') }}">

<header id="app-main-header">
    <nav class="app-nav-menu">
        <a href="{{ route('products.index') }}"
           class="app-nav-menu__link {{ request()->routeIs('products.*') ? 'app-nav-menu__link--active' : '' }}">
            Product
        </a>
        <a href="{{ route('shops.index') }}"
           class="app-nav-menu__link {{ request()->routeIs('shops.*') ? 'app-nav-menu__link--active' : '' }}">
            Shop
        </a>
    </nav>
</header>
<main id="app-main-content">
    <header class="app-cmp-content-header">
        <h1 class="app-cmp-content-header__title">
            @section('title'){{ $title }}@show
        </h1>
        @yield('header')
    </header>
    @yield('content')
</main>
<footer id="app-main-footer" class="app-main-footer">
    &#xA9; Copyright Lelarat's Database.
</footer>