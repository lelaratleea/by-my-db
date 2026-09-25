{{-- shops/create-form.blade.php --}}
@extends('shops.main', [
    'title' => 'Create',
])

@section('content')
    <section class="app-form-section">
        <form action="{{ route('shops.create') }}" method="post" class="app-form">
            @csrf

            <div class="app-form__field">
                <label class="app-form__label" for="code">Code</label>
                <input class="app-form__input" type="text" id="code" name="code" required />
            </div>

            <div class="app-form__field">
                <label class="app-form__label" for="name">Name</label>
                <input class="app-form__input" type="text" id="name" name="name" required />
            </div>

            <div class="app-form__field">
                <label class="app-form__label" for="owner">Owner</label>
                <input class="app-form__input" type="text" id="owner" name="owner" required />
            </div>

            <div class="app-form__field">
                <label class="app-form__label" for="latitude">Latitude</label>
                <input class="app-form__input" type="number" step="any" id="latitude" name="latitude" required />
            </div>

            <div class="app-form__field">
                <label class="app-form__label" for="longitude">Longitude</label>
                <input class="app-form__input" type="number" step="any" id="longitude" name="longitude" required />
            </div>

            <div class="app-form__field">
                <label class="app-form__label" for="address">Address</label>
                <textarea class="app-form__textarea" id="address" name="address" cols="80" rows="10" required></textarea>
            </div>

            <div class="app-form__actions">
                <button type="submit" class="app-form__submit">Create</button>
                <a href="{{
                    session()->get('bookmarks.shops.create-form') ??
                    route('shops.list')
                }}" class="app-form__cancel">Cancel</a>
            </div>
        </form>
    </section>
@endsection