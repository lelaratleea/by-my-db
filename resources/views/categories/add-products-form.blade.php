@extends('categories.main', [
    'title' => "{$category->code}: Add Products",
])

@section('header')
<search>
    <form action="{{ route('categories.add-products-form', [
        'category' => $category->code,
    ]) }}" method="get" class="app-cmp-search-form">
        <fieldset>
            <legend>Search</legend>
            <label>
                <b>Term</b>
                <input type="text" name="term" value="{{ $criteria['term'] }}" />
            </label>
            <br />
            <label>
                Min Price
                <input type="number" name="minPrice" value="{{ $criteria['minPrice'] }}" step="0.01" />
            </label><br />
            <label>
                Max Price
                <input type="number" name="maxPrice" value="{{ $criteria['maxPrice'] }}" step="0.01" />
            </label><br />
            <button type="submit">Filter</button>
            <a href="{{ route('categories.add-products-form', [
                'category' => $category->code,
            ]) }}">Clear</a>
        </fieldset>
    </form>
</search>

<form action="{{ route('categories.add-product', [
    'category' => $category->code,
]) }}" id="app-form-add-product" method="post" class="app-cmp-hidden-form">
    @csrf
</form>

<div class="app-cmp-links-bar">
    <nav>
        <ul class="app-cmp-links">
            <li>
                <a href="{{ route('categories.view-products', [
                    'category' => $category->code,
                ]) }}">&lt; Back</a>
            </li>
        </ul>
    </nav>
    {{ $products->withQueryString()->links() }}
</div>
@endsection

@section('content')
    <section class="app-list-section">
        <h2 class="app-list-section__heading">List of Products for Adding to {{ $category->name }}</h2>

        <table class="app-data-table">
            <caption class="app-data-table__caption">List of Products for Adding to {{ $category->name }}</caption>
            <thead class="app-data-table__head">
                <tr>
                    <th class="app-data-table__th app-data-table__th--code">Code</th>
                    <th class="app-data-table__th app-data-table__th--name">Name</th>
                    <th class="app-data-table__th">Category</th>
                    <th class="app-data-table__th">Price</th>
                    <th class="app-data-table__th">No. of Shops</th>
                    <th class="app-data-table__th app-data-table__th--actions"></th>
                </tr>
            </thead>
            <tbody class="app-data-table__body">
                @foreach($products as $product)
                    <tr class="app-data-table__row">
                        <td class="app-data-table__td app-data-table__td--code">
                            <a href="{{ route('products.view', [
                                'product' => $product->code,
                            ]) }}" class="app-data-table__link">
                                {{ $product->code }}
                            </a>
                        </td>
                        <td class="app-data-table__td app-data-table__td--name">
                            {{ $product->name }}
                        </td>
                        <td class="app-data-table__td">
                            {{-- ตาม spec: category ในหน้านี้ห้ามมีลิงก์ --}}
                            {{ $product->category?->name }}
                        </td>
                        <td class="app-data-table__td">{{ number_format($product->price, 2) }}</td>
                        <td class="app-data-table__td">{{ $product->shops_count }}</td>
                        <td class="app-data-table__td app-data-table__td--actions">
                            <button type="submit"
                                form="app-form-add-product"
                                name="product" value="{{ $product->code }}">
                                Add
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection