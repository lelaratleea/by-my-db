@extends('users.main', [
    'title' => 'List',
])

@section('header')
<search>
    <form action="{{ route('users.list') }}" method="get" class="app-cmp-search-form">
        <fieldset>
            <legend>Search</legend>
            <label>
                <b>Term</b>
                <input type="text" name="term" value="{{ $criteria['term'] }}" />
            </label>
            <br />
            <button type="submit">Filter</button>
            <a href="{{ route('users.list') }}">Clear</a>
        </fieldset>
    </form>
</search>

@php
    session()->put('bookmarks.users.create-form', url()->full());
    session()->put(
        'bookmarks.users.create',
        session()->get('bookmarks.users.create-form'),
    );
@endphp

<div class="app-cmp-links-bar">
    <nav>
        <ul class="app-cmp-links">
            <li>
                <a href="{{ route('users.create-form') }}">Create User</a>
            </li>
        </ul>
    </nav>
    {{ $users->withQueryString()->links() }}
</div>
@endsection

@section('content')
    <section class="app-list-section">
        <h2 class="app-list-section__heading">List of Users</h2>

        <table class="app-data-table">
            <caption class="app-data-table__caption">List of Users</caption>
            <thead class="app-data-table__head">
                <tr>
                    <th class="app-data-table__th">Email</th>
                    <th class="app-data-table__th">Name</th>
                    <th class="app-data-table__th">Role</th>
                </tr>
            </thead>
            <tbody class="app-data-table__body">
                @php
                    session()->put('bookmarks.users.view', url()->full());
                @endphp
                @foreach($users as $user)
                    <tr class="app-data-table__row">
                        <td class="app-data-table__td">
                            <a href="{{ route('users.view', [
                                'user' => $user->email,
                            ]) }}" class="app-data-table__link">
                                {{ $user->email }}
                            </a>
                        </td>
                        <td class="app-data-table__td">{{ $user->name }}</td>
                        <td class="app-data-table__td">{{ $user->role }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection