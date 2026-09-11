@extends('products.main', [
    'title' => $product->name,
])

@section('header')
<nav>
    <form action="{{ route('products.delete', [
        'product' => $product->code,
    ]) }}" method="post"
        id="app-form-delete">
        @csrf
    </form>
<ul class="app-action-links">
    <li>
        <a href="{{ route('products.view-shops', [
            'product' => $product->code,
        ]) }}" class="app-action-links__view-shops">View Shops</a>
    </li>
    <li>
        <a href="{{ route('products.update-form', [
            'product' => $product->code,
        ]) }}" class="app-action-links__update">Update</a>
    </li>
    <li>
        <button type="submit" form="app-form-delete" class="app-action-links__delete">Delete</button>
    </li>
</ul>
</nav>
@endsection

@section('content')
    <section class="app-detail-section">
        <dl class="app-detail-list">
            <dt class="app-detail-list__term">Code</dt>
            <dd class="app-detail-list__desc">{{ $product->code }}</dd>

            <dt class="app-detail-list__term">Name</dt>
            <dd class="app-detail-list__desc">{{ $product->name }}</dd>

            <dt class="app-detail-list__term">Category</dt>
<dd class="app-detail-list__desc">
    @if($product->category)
        [<a href="{{ route('categories.view', ['category' => $product->category->code]) }}">{{ $product->category->code }}</a>] {{ $product->category->name }}
    @endif
</dd>

            <dt class="app-detail-list__term">Price</dt>
            <dd class="app-detail-list__desc">{{ number_format((float)$product->price, 2) }}</dd>
        </dl>
        <pre class="app-detail-description">{{ $product->description }}</pre>
    </section>
@endsection