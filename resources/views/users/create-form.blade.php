@extends('users.main', [
    'title' => 'Create',
])

@section('content')
<section class="app-form-section">
    @include('users.errors')
    <form action="{{ route('users.create') }}" method="post" class="app-form">
        @csrf
        <div class="app-form__field">
            <label class="app-form__label" for="email">Email *</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}"
                class="app-form__input" required />
        </div>
        <div class="app-form__field">
            <label class="app-form__label" for="password">Password *</label>
            <input type="password" id="password" name="password"
                class="app-form__input" required />
        </div>
        <div class="app-form__field">
            <label class="app-form__label" for="name">Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}"
                class="app-form__input" required />
        </div>
        <div class="app-form__field">
            <label class="app-form__label" for="role">Role *</label>
            <select id="role" name="role" class="app-form__input" required>
                <option value="" disabled @selected(old('role') === null)>--- Please Select Role ---</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}" @selected(old('role') === $role)>{{ $role }}</option>
                @endforeach
            </select>
        </div>
        <div class="app-form__actions">
            <button type="submit" class="app-form__submit">Create</button>
            <a href="{{
                session()->get('bookmarks.users.create-form') ??
                route('users.list')
            }}" class="app-form__cancel">Cancel</a>
        </div>
    </form>
</section>
@endsection