{{-- categories/list.blade.php --}}
@extends('categories.main', [
    'title' => 'List',
])

@section('header')
<search>
    <form action="{{ route('categories.list') }}" method="get" class="app-cmp-search-form">
        <fieldset>
            <legend>Search</legend>
            <label>
                <b>Term</b>
                <input type="text" name="term" value="{{ $criteria['term'] }}" />
            </label>
            <br />
            <button type="submit">Filter</button>
            <a href="{{ route('categories.list') }}">Clear</a>
        </fieldset>
    </form>
</search>

@php
    session()->put('bookmarks.categories.create-form', url()->full());
    session()->put(
        'bookmarks.categories.create',
        session()->get('bookmarks.categories.create-form'),
    );
@endphp

<div class="app-cmp-links-bar">
    <nav>
        <ul class="app-cmp-links">
            <li>
                <a href="{{ route('categories.create-form') }}">Create Category</a>
            </li>
        </ul>
    </nav>
    {{ $categories->withQueryString()->links() }}
</div>
@endsection

@section('content')
    <section class="app-list-section">
        <h2 class="app-list-section__heading">List of Categories</h2>

        <table class="app-data-table">
            <caption class="app-data-table__caption">List of Categories</caption>
            <thead class="app-data-table__head">
                <tr>
                    <th class="app-data-table__th app-data-table__th--code">Code</th>
                    <th class="app-data-table__th app-data-table__th--name">Name</th>
                    <th class="app-data-table__th">No. of Products</th>
                </tr>
            </thead>
            <tbody class="app-data-table__body">
                @php
                    session()->put('bookmarks.categories.view', url()->full());
                @endphp
                @foreach($categories as $category)
                    <tr class="app-data-table__row">
                        <td class="app-data-table__td app-data-table__td--code">
                            <a href="{{ route('categories.view', [
                                'category' => $category->code,
                            ]) }}" class="app-data-table__link">
                                {{ $category->code }}
                            </a>
                        </td>
                        <td class="app-data-table__td app-data-table__td--name">
                            {{ $category->name }}
                        </td>
                        <td class="app-data-table__td">
                            {{ $category->products_count }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection