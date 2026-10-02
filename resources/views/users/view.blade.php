@extends('users.main', [
    'title' => $user->email,
])

@section('header')
@can('delete', $user)
    <form action="{{ route('users.delete', [
        'user' => $user->email,
    ]) }}" method="post" id="app-form-delete">
        @csrf
    </form>
@endcan

<ul class="app-action-links">
    <li>
        <a href="{{
            session()->get('bookmarks.users.view') ??
            route('users.list')
        }}" class="app-action-links__back">&lt; Back</a>
    </li>
    @can('update', $user)
        <li>
            <a href="{{ route('users.update-form', [
                'user' => $user->email,
            ]) }}" class="app-action-links__update">Update</a>
        </li>
    @endcan
    @can('delete', $user)
        <li>
            <button type="submit" form="app-form-delete"
                class="app-action-links__delete">Delete</button>
        </li>
    @endcan
</ul>
@endsection

@section('content')
    <section class="app-detail-section">
        <dl class="app-detail-list">
            <dt class="app-detail-list__term">Email</dt>
            <dd class="app-detail-list__desc">{{ $user->email }}</dd>

            <dt class="app-detail-list__term">Name</dt>
            <dd class="app-detail-list__desc">{{ $user->name }}</dd>

            <dt class="app-detail-list__term">Role</dt>
            <dd class="app-detail-list__desc">{{ $user->role }}</dd>
        </dl>
    </section>
@endsection