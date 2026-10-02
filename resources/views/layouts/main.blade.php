<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<header id="app-main-header">
    <nav class="app-nav-menu">
        <a href="{{ route('products.list') }}"
           class="app-nav-menu__link {{ request()->routeIs('products.*') ? 'app-nav-menu__link--active' : '' }}">
            Product
        </a>
        <a href="{{ route('shops.list') }}"
           class="app-nav-menu__link {{ request()->routeIs('shops.*') ? 'app-nav-menu__link--active' : '' }}">
            Shop
        </a>
        <a href="{{ route('categories.list') }}"
           class="app-nav-menu__link {{ request()->routeIs('categories.*') ? 'app-nav-menu__link--active' : '' }}">
            Category
        </a>
    </nav>

    @auth
        <form action="{{ route('logout') }}" method="post" id="app-form-logout">
            @csrf
        </form>
        <ul class="app-cmp-user-panel">
            <li>{{ \Auth::user()->name }}</li>
            <li>
                <button type="submit" form="app-form-logout"
                        class="app-cmp-user-panel__logout">Logout</button>
            </li>
        </ul>
    @endauth
</header>
<main id="app-main-content">
    <header class="app-cmp-content-header">
        <h1 class="app-cmp-content-header__title">
            @section('title'){{ $title }}@show
        </h1>
        <div class="app-cmp-notifications">
            @session('status')
                <div role="status" class="app-cmp-notifications__message">{{ $value }}</div>
            @endsession
        </div>
        @yield('header')
    </header>
    @yield('content')
</main>
<footer id="app-main-footer" class="app-main-footer">
    &#xA9; Copyright Lelarat's Database.
</footer>
</body>
</html>