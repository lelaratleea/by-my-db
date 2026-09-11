@extends('categories.main', [
    'title' => "{$category->code}: Products",
])

@section('header')
<search>
    <form action="{{ route('categories.view-products', ['category' => $category->code]) }}" method="get" class="app-cmp-search-form">
        <fieldset>
            <legend>Search</legend>
            <label><b>Search</b>
                <input type="text" name="term" value="{{ $criteria['term'] }}" />
            </label><br />
            <label>Min Price
                <input type="number" name="minPrice" value="{{ $criteria['minPrice'] }}" step="0.01" />
            </label><br />
            <label>Max Price
                <input type="number" name="maxPrice" value="{{ $criteria['maxPrice'] }}" step="0.01" />
            </label><br />
            <button type="submit">Filter</button>
            <a href="{{ route('categories.view-products', ['category' => $category->code]) }}">Clear</a>
        </fieldset>
    </form>
</search>

<div class="app-cmp-links-bar">
    <nav>
        <ul class="app-cmp-links">
            <li><a href="{{ route('categories.view', ['category' => $category->code]) }}">&lt; Back</a></li>
        </ul>
    </nav>
    {{ $products->withQueryString()->links() }}
</div>
@endsection

@section('content')
    <section class="app-list-section">
        <h2 class="app-list-section__heading">List of Products for {{ $category->name }}</h2>
        <table class="app-data-table">
            <caption class="app-data-table__caption">List of Products for {{ $category->name }}</caption>
            <thead class="app-data-table__head">
                <tr>
                    <th class="app-data-table__th app-data-table__th--code">Code</th>
                    <th class="app-data-table__th app-data-table__th--name">Name</th>
                    <th class="app-data-table__th">Price</th>
                    <th class="app-data-table__th">No. of Shops</th>
                </tr>
            </thead>
            <tbody class="app-data-table__body">
                @foreach($products as $product)
                    <tr class="app-data-table__row">
                        <td class="app-data-table__td app-data-table__td--code">
                            <a href="{{ route('products.view', ['product' => $product->code]) }}" class="app-data-table__link">
                                {{ $product->code }}
                            </a>
                        </td>
                        <td class="app-data-table__td app-data-table__td--name">{{ $product->name }}</td>
                        <td class="app-data-table__td">{{ number_format($product->price, 2) }}</td>
                        <td class="app-data-table__td">{{ $product->shops_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection