@extends('categories.main', [
    'title' => $category->name,
])

@section('header')
@can('delete', $category)
    <form action="{{ route('categories.delete', ['category' => $category->code]) }}" method="post" id="app-form-delete">
        @csrf
        @method('DELETE')
    </form>
@endcan
<ul class="app-action-links">
    <li>
        <a href="{{
            session()->get('bookmarks.categories.view') ??
            route('categories.list')
        }}" class="app-action-links__back">&lt; Back</a>
    </li>
    <li>
        <a href="{{ route('categories.view-products', ['category' => $category->code]) }}" class="app-action-links__view-shops">View Products</a>
    </li>
    @can('update', $category)
        <li>
            <a href="{{ route('categories.update-form', ['category' => $category->code]) }}" class="app-action-links__update">Update</a>
        </li>
    @endcan
    @can('delete', $category)
        <li>
            <button type="submit" form="app-form-delete" class="app-action-links__delete">Delete</button>
        </li>
    @endcan
</ul>
@endsection

@section('content')
    <section class="app-detail-section">
        <dl class="app-detail-list">
            <dt class="app-detail-list__term">Code</dt>
            <dd class="app-detail-list__desc">{{ $category->code }}</dd>

            <dt class="app-detail-list__term">Name</dt>
            <dd class="app-detail-list__desc">{{ $category->name }}</dd>
        </dl>
        <pre class="app-detail-description">{{ $category->description }}</pre>
    </section>
@endsection