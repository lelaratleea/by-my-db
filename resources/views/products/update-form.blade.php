@extends('products.main', [
    'title' => $product->code,
])

@section('content')
<section class="app-form-section">
    <form action="{{ route('products.update', [
        'product' => $product->code,
    ]) }}" method="post" class="app-form">
        @csrf
        <div class="app-form__field">
            <label class="app-form__label">Code</label>
            <input type="text" name="code" value="{{ $product->code }}" class="app-form__input" required />
        </div>
        <div class="app-form__field">
            <label class="app-form__label">Name</label>
            <input type="text" name="name" value="{{ $product->name }}" class="app-form__input" required />
        </div>
        <div class="app-form__field">
            <label class="app-form__label">Category</label>
            <select name="category" class="app-form__input" required>
                @foreach($categories as $category)
                    <option value="{{ $category->code }}"
                        @selected($category->code === $product->category->code)>
                        [{{ $category->code }}] {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="app-form__field">
            <label class="app-form__label">Price</label>
            <input type="number" step="any" name="price"
                value="{{ $product->price }}" class="app-form__input" required />
        </div>
        <div class="app-form__field">
            <label class="app-form__label">Description</label>
            {{-- No space between <textarea> and {{ $product->description }} --}}
            <textarea
                name="description" class="app-form__textarea" cols="80" rows="10" required
            >{{ $product->description }}</textarea>
        </div>
        <div class="app-form__actions">
            <button type="submit" class="app-form__submit">Update</button>
            <a href="{{
                session()->get('bookmarks.products.update-form') ??
                route('products.view', [
                    'product' => $product->code,
                ])
            }}" class="app-form__cancel">Cancel</a>
        </div>
    </form>
</section>
@endsection