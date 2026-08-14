@extends('shops.main', [
    'title' => 'List',
])

@section('content')
    <section class="app-list-section">
        <h2 class="app-list-section__heading">List of Shops</h2>

        <table class="app-data-table">
            <caption class="app-data-table__caption">List of Shops</caption>
            <thead class="app-data-table__head">
                <tr>
                    <th class="app-data-table__th app-data-table__th--code">Code</th>
                    <th class="app-data-table__th app-data-table__th--name">Name</th>
                    <th class="app-data-table__th app-data-table__th--owner">Owner</th>
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
                        <td class="app-data-table__td app-data-table__td--owner">
                            {{ $shop->owner }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection