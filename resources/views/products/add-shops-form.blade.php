@extends('products.main', [
    'title' => "{$product->code}: Add Shops",
])

@section('header')
<search>
    <form action="{{ route('products.add-shops-form', [
        'product' => $product->code,
    ]) }}" method="get" class="app-cmp-search-form">
        <fieldset>
            <legend>Search</legend>
            <label>
                <b>Search</b>
                <input type="text" name="term" value="{{ $criteria['term'] }}" />
            </label>
            <br />
            <button type="submit">Filter</button>
            <a href="{{ route('products.add-shops-form', [
                'product' => $product->code,
            ]) }}">Clear</a>
        </fieldset>
    </form>
</search>

<form action="{{ route('products.add-shop', [
    'product' => $product->code,
]) }}" id="app-form-add-shop" method="post" class="app-cmp-hidden-form">
    @csrf
</form>

<div class="app-cmp-links-bar">
    <nav>
        <ul class="app-cmp-links">
            <li>
                <a href="{{
                    session()->get('bookmarks.products.add-shops-form') ??
                    route('products.view-shops', ['product' => $product->code])
                }}">&lt; Back</a>
            </li>
        </ul>
    </nav>
    {{ $shops->withQueryString()->links() }}
</div>
@endsection

@section('content')
    <section class="app-list-section">
        <h2 class="app-list-section__heading">List of Shops for Adding to {{ $product->name }}</h2>

        <table class="app-data-table">
            <caption class="app-data-table__caption">List of Shops for Adding to {{ $product->name }}</caption>
            <thead class="app-data-table__head">
                <tr>
                    <th class="app-data-table__th app-data-table__th--code">Code</th>
                    <th class="app-data-table__th app-data-table__th--name">Name</th>
                    <th class="app-data-table__th">Owner</th>
                    <th class="app-data-table__th">No. of Products</th>
                    <th class="app-data-table__th app-data-table__th--actions"></th>
                </tr>
            </thead>
            <tbody class="app-data-table__body">
                @php
                    session()->put('bookmarks.shops.view', url()->full());
                @endphp
                @foreach($shops as $shop)
                    <tr class="app-data-table__row">
                        <td class="app-data-table__td app-data-table__td--code">
                            <a href="{{ route('shops.view', [
                                'shop' => $shop->code,
                            ]) }}" class="app-data-table__link">
                                {{ $shop->code }}
                            </a>
                        </td>
                        <td class="app-data-table__td app-data-table__td--name">
                            {{ $shop->name }}
                        </td>
                        <td class="app-data-table__td">{{ $shop->owner }}</td>
                        <td class="app-data-table__td">{{ $shop->products_count }}</td>
                        <td class="app-data-table__td app-data-table__td--actions">
                            <button type="submit"
                                form="app-form-add-shop"
                                name="shop" value="{{ $shop->code }}">
                                Add
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection