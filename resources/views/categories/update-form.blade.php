@extends('categories.main', [
    'title' => $category->code,
])

@section('content')
    <section class="app-form-section">
        <form action="{{ route('categories.update', ['category' => $category->code]) }}" method="post" class="app-form">
            @csrf
            <div class="app-form__field">
                <label class="app-form__label" for="code">Code</label>
                <input class="app-form__input" type="text" id="code" name="code" value="{{ $category->code }}" required />
            </div>
            <div class="app-form__field">
                <label class="app-form__label" for="name">Name</label>
                <input class="app-form__input" type="text" id="name" name="name" value="{{ $category->name }}" required />
            </div>
            <div class="app-form__field">
                <label class="app-form__label" for="description">Description</label>
                <textarea class="app-form__textarea" id="description" name="description" cols="80" rows="10" required>{{ $category->description }}</textarea>
            </div>
            <button type="submit" class="app-form__submit">Update</button>
        </form>
    </section>
@endsection