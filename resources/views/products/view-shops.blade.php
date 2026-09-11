@extends('products.main', [
    'title' => "{$product->code}: Shops",
])

@section('header')
<search>
    <form action="{{ route('products.view-shops', [
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
            <a href="{{ route('products.view-shops', [
                'product' => $product->code,
            ]) }}">Clear</a>
        </fieldset>
    </form>
</search>

<div class="app-cmp-links-bar">
    <nav>
        <ul class="app-cmp-links">
            <li>
                <a href="{{ route('products.view', [
                    'product' => $product->code,
                ]) }}">&lt; Back</a>
            </li>
        </ul>
    </nav>
    {{ $shops->withQueryString()->links() }}
</div>
@endsection

@section('content')
    <section class="app-list-section">
        <h2 class="app-list-section__heading">List of Shops for {{ $product->name }}</h2>

        <table class="app-data-table">
            <caption class="app-data-table__caption">List of Shops for {{ $product->name }}</caption>
            <thead class="app-data-table__head">
                <tr>
                    <th class="app-data-table__th app-data-table__th--code">Code</th>
                    <th class="app-data-table__th app-data-table__th--name">Name</th>
                    <th class="app-data-table__th">Owner</th>
                    <th class="app-data-table__th">No. of Products</th>
                </tr>
            </thead>
            <tbody class="app-data-table__body">
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
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection