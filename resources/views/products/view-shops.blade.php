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

<form action="{{ route('products.remove-shop', [
    'product' => $product->code,
]) }}" id="app-form-remove-shop" method="post">
    @csrf
</form>

<div class="app-cmp-links-bar">
    <nav>
        <ul class="app-cmp-links">
            <li>
                <a href="{{ route('products.view', [
                    'product' => $product->code,
                ]) }}">&lt; Back</a>
            </li>
            <li>
                <a href="{{ route('products.add-shops-form', [
                    'product' => $product->code,
                ]) }}">Add Shops</a>
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
                    <th class="app-data-table__th"></th>
                </tr>
            </thead>
            <tbody class="app-data-table__body">
                @foreach($shops as $shop)
                    <tr class="app-data-table__row">
                        <td class="app-data-table__td app-data-table__td--code">
                            {{ $shop->code }}
                        </td>
                        <td class="app-data-table__td app-data-table__td--name">
                            {{ $shop->name }}
                        </td>
                        <td class="app-data-table__td">{{ $shop->owner }}</td>
                        <td class="app-data-table__td">{{ $shop->products_count }}</td>
                        <td class="app-data-table__td">
                            <button type="submit"
                                form="app-form-remove-shop"
                                name="shop" value="{{ $shop->code }}">
                                Remove
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection