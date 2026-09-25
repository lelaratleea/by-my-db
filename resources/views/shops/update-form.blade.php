{{-- shops/update-form.blade.php --}}
@extends('shops.main', [
    'title' => $shop->code,
])

@section('content')
    <section class="app-form-section">
        <form action="{{ route('shops.update', ['shop' => $shop->code]) }}" method="post" class="app-form">
            @csrf

            <div class="app-form__field">
                <label class="app-form__label" for="code">Code</label>
                <input class="app-form__input" type="text" id="code" name="code" value="{{ $shop->code }}" required />
            </div>

            <div class="app-form__field">
                <label class="app-form__label" for="name">Name</label>
                <input class="app-form__input" type="text" id="name" name="name" value="{{ $shop->name }}" required />
            </div>

            <div class="app-form__field">
                <label class="app-form__label" for="owner">Owner</label>
                <input class="app-form__input" type="text" id="owner" name="owner" value="{{ $shop->owner }}" required />
            </div>

            <div class="app-form__field">
                <label class="app-form__label" for="latitude">Latitude</label>
                <input class="app-form__input" type="number" step="any" id="latitude" name="latitude" value="{{ $shop->latitude }}" required />
            </div>

            <div class="app-form__field">
                <label class="app-form__label" for="longitude">Longitude</label>
                <input class="app-form__input" type="number" step="any" id="longitude" name="longitude" value="{{ $shop->longitude }}" required />
            </div>

            <div class="app-form__field">
                <label class="app-form__label" for="address">Address</label>
                <textarea class="app-form__textarea" id="address" name="address" cols="80" rows="10" required>{{ $shop->address }}</textarea>
            </div>

            <div class="app-form__actions">
                <button type="submit" class="app-form__submit">Update</button>
                <a href="{{
                    session()->get('bookmarks.shops.update-form') ??
                    route('shops.view', ['shop' => $shop->code])
                }}" class="app-form__cancel">Cancel</a>
            </div>
        </form>
    </section>
@endsection