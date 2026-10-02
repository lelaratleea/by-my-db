@extends('products.main', [
    'title' => $product->name,
])

@section('header')

<nav>
@can('delete', $product)
    <form action="{{ route('products.delete', [
        'product' => $product->code,
    ]) }}" method="post" id="app-form-delete">
        @csrf
        @method('DELETE')
    </form>
@endcan

@php
    session()->put(
        'bookmarks.products.delete',
        session()->get('bookmarks.products.view'),
    );
    session()->put('bookmarks.products.update-form', url()->full());
    session()->put(
        'bookmarks.products.update',
        session()->get('bookmarks.products.view'),
    );
    session()->put('bookmarks.products.view-shops', url()->full());
@endphp

<ul class="app-action-links">
    <li>
        <a href="{{
            session()->get('bookmarks.products.view') ??
            route('products.list')
        }}" class="app-action-links__back">&lt; Back</a>
    </li>
    @can('view', $product)
        <li>
            <a href="{{ route('products.view-shops', [
                'product' => $product->code,
            ]) }}" class="app-action-links__view-shops">View Shops</a>
        </li>
    @endcan
    @can('update', $product)
        <li>
            <a href="{{ route('products.update-form', [
                'product' => $product->code,
            ]) }}" class="app-action-links__update">Update</a>
        </li>
    @endcan
    @can('delete', $product)
        <li>
            <button type="submit" form="app-form-delete" class="app-action-links__delete">Delete</button>
        </li>
    @endcan
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