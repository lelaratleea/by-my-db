@extends('shops.main', [
    'title' => "{$shop->code}: Add Products",
])

@section('header')
    <search>
        <form action="{{ route('shops.add-products-form', [
            'shop' => $shop->code,
        ]) }}" method="get"
            class="app-cmp-search-form">
            <fieldset>
                <legend>Search</legend>
                <label>
                    <b>Term</b>
                    <input type="text" name="term" value="{{ $criteria['term'] }}" />
                </label>
                <label>
                    <b>Min Price</b>
                    <input type="number" name="minPrice" value="{{ $criteria['minPrice'] }}" step="0.01" />
                </label>
                <label>
                    <b>Max Price</b>
                    <input type="number" name="maxPrice" value="{{ $criteria['maxPrice'] }}" step="0.01" />
                </label>
                <button type="submit">Filter</button>
                <a
                    href="{{ route('shops.add-products-form', [
                        'shop' => $shop->code,
                    ]) }}">Clear</a>
            </fieldset>
        </form>
    </search>

    @can('update', $shop)
        <form action="{{ route('shops.add-product', [
            'shop' => $shop->code,
        ]) }}" id="app-form-add-product"
            method="post" class="app-cmp-hidden-form">
            @csrf
        </form>
    @endcan

    <div class="app-cmp-links-bar">
        <nav>
            <ul class="app-cmp-links">
                <li>
                    <a
                        href="{{ session()->get('bookmarks.shops.add-products-form') ?? route('shops.view-products', ['shop' => $shop->code]) }}">&lt;
                        Back</a>
                </li>
            </ul>
        </nav>
        {{ $products->withQueryString()->links() }}
    </div>
@endsection

@section('content')
    <section class="app-list-section">
        <h2 class="app-list-section__heading">List of Products for Adding to {{ $shop->name }}</h2>

        <table class="app-data-table">
            <caption class="app-data-table__caption">List of Products for Adding to {{ $shop->name }}</caption>
            <thead class="app-data-table__head">
                <tr>
                    <th class="app-data-table__th app-data-table__th--code">Code</th>
                    <th class="app-data-table__th app-data-table__th--name">Name</th>
                    <th class="app-data-table__th">Category</th>
                    <th class="app-data-table__th">Price</th>
                    <th class="app-data-table__th">No. of Shops</th>
                    @can('update', $shop)
                        <th class="app-data-table__th app-data-table__th--actions"></th>
                    @endcan
                </tr>
            </thead>
            <tbody class="app-data-table__body">
                @php
                    session()->put('bookmarks.products.view', url()->full());
                @endphp
                @foreach ($products as $product)
                    <tr class="app-data-table__row">
                        <td class="app-data-table__td app-data-table__td--code">
                            <a href="{{ route('products.view', [
                                'product' => $product->code,
                            ]) }}"
                                class="app-data-table__link">
                                {{ $product->code }}
                            </a>
                        </td>
                        <td class="app-data-table__td app-data-table__td--name">
                            {{ $product->name }}
                        </td>
                        <td class="app-data-table__td">
                            @if ($product->category)
                                <a href="{{ route('categories.view', [
                                    'category' => $product->category->code,
                                ]) }}"
                                    class="app-data-table__link">
                                    {{ $product->category->name }}
                                </a>
                            @endif
                        </td>
                        <td class="app-data-table__td">{{ number_format($product->price, 2) }}</td>
                        <td class="app-data-table__td">{{ $product->shops_count }}</td>
                        @can('update', $shop)
                            <td class="app-data-table__td app-data-table__td--actions">
                                <button type="submit" form="app-form-add-product" name="product" value="{{ $product->code }}"
                                    class="app-data-table__add">
                                    Add
                                </button>
                            </td>
                        @endcan
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection