@extends('users.main', [
    'title' => 'Self',
])

@section('content')
<section class="app-form-section">
    @include('users.errors')
    <form action="{{ route('users.selves.update') }}" method="post" class="app-form">
        @csrf
        <div class="app-form__field">
            <span class="app-form__label">Email</span>
            <span class="app-form__static">{{ $user->email }}</span>
        </div>
        <div class="app-form__field">
            <label class="app-form__label" for="name">Name *</label>
            <input type="text" id="name" name="name"
                value="{{ old('name', $user->name) }}"
                class="app-form__input" required />
        </div>
        <div class="app-form__field">
            <label class="app-form__label" for="password">Password</label>
            <input type="password" id="password" name="password"
                placeholder="Leave blank if you don't want to update"
                class="app-form__input" />
        </div>
        <div class="app-form__actions">
            <button type="submit" class="app-form__submit">Update</button>
            <a href="{{
                session()->get('bookmarks.users.selves.update-form') ??
                route('users.selves.view')
            }}" class="app-form__cancel">Cancel</a>
        </div>
    </form>
</section>
@endsection