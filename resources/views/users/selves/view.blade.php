@extends('users.main', [
    'title' => 'Self',
])

@section('header')
<ul class="app-action-links">
    <li>
        <a href="{{
            session()->get('bookmarks.users.selves.view') ?? '/'
        }}" class="app-action-links__back">&lt; Back</a>
    </li>
    <li>
        <a href="{{ route('users.selves.update-form') }}"
            class="app-action-links__update">Update Self</a>
    </li>
</ul>
@endsection

@section('content')
    <section class="app-detail-section">
        <dl class="app-detail-list">
            <dt class="app-detail-list__term">Email</dt>
            <dd class="app-detail-list__desc">{{ $user->email }}</dd>

            <dt class="app-detail-list__term">Name</dt>
            <dd class="app-detail-list__desc">{{ $user->name }}</dd>
        </dl>
    </section>
@endsection