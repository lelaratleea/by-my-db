@extends('categories.main', [
    'title' => 'Create',
])

@section('content')
    <section class="app-form-section">
        <form action="{{ route('categories.create') }}" method="post" class="app-form">
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
                <label class="app-form__label" for="description">Description</label>
                <textarea class="app-form__textarea" id="description" name="description" cols="80" rows="10" required></textarea>
            </div>
            <div class="app-form__actions">
                <button type="submit" class="app-form__submit">Create</button>
                <a href="{{
                    session()->get('bookmarks.categories.create-form') ??
                    route('categories.list')
                }}" class="app-form__cancel">Cancel</a>
            </div>
        </form>
    </section>
@endsection