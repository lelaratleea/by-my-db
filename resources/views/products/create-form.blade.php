@extends('products.main', [
    'title' => 'Create',
])

@section('content')
<section class="app-form-section">
    <form action="{{ route('products.create') }}" method="post" class="app-form">
        @csrf
        <div class="app-form__field">
            <label class="app-form__label">Code</label>
            <input type="text" name="code" class="app-form__input" required />
        </div>
        <div class="app-form__field">
            <label class="app-form__label">Name</label>
            <input type="text" name="name" class="app-form__input" required />
        </div>
        <div class="app-form__field">
            <label class="app-form__label">Category</label>
            <select name="category" class="app-form__input" required>
                <option value="" selected disabled>--- Please Select Category ---</option>
                @foreach($categories as $category)
                    <option value="{{ $category->code }}">
                        [{{ $category->code }}] {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="app-form__field">
            <label class="app-form__label">Price</label>
            <input type="number" step="any" name="price" class="app-form__input" required />
        </div>
        <div class="app-form__field">
            <label class="app-form__label">Description</label>
            <textarea name="description" class="app-form__textarea" cols="80" rows="10" required></textarea>
        </div>
        <button type="submit" class="app-form__submit">Create</button>
    </form>
</section>
@endsection