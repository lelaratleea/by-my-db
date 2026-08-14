@extends('products.main', [
    'title' => 'List',
])

@section('content')
    <section class="app-list-section">
        <h2 class="app-list-section__heading">List of Products</h2>

        <table class="app-data-table">
            <caption class="app-data-table__caption">List of Products</caption>
            <thead class="app-data-table__head">
                <tr>
                    <th class="app-data-table__th app-data-table__th--code">Code</th>
                    <th class="app-data-table__th app-data-table__th--name">Name</th>
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
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection