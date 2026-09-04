{{-- shops/create-form.blade.php --}}
@extends('Shops.main', [
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

            <button type="submit" class="app-form__submit">Create</button>
        </form>
    </section>
@endsection