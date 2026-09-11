{{-- shops/view.blade.php --}}
@extends('Shops.main', [
    'title' => $shop->name,
])

@section('header')
<form action="{{ route('shops.delete', ['shop' => $shop->code]) }}" method="post" id="app-form-delete">
    @csrf
    @method('delete')
</form>

<ul class="app-action-links">
    <li>
        <a href="{{ route('shops.view-products', ['shop' => $shop->code]) }}" class="app-action-links__view-shops">
            View Products
        </a>
    </li>
    <li>
        <a href="{{ route('shops.update-form', ['shop' => $shop->code]) }}" class="app-action-links__update">
            Update
        </a>
    </li>
    <li>
        <button type="submit" form="app-form-delete" class="app-action-links__delete">
            Delete
        </button>
    </li>
</ul>
@endsection

@section('content')
    <section class="app-detail-section">
        <dl class="app-detail-list">
            <dt class="app-detail-list__term">Code</dt>
            <dd class="app-detail-list__desc">{{ $shop->code }}</dd>

            <dt class="app-detail-list__term">Name</dt>
            <dd class="app-detail-list__desc">{{ $shop->name }}</dd>

            <dt class="app-detail-list__term">Owner</dt>
            <dd class="app-detail-list__desc">{{ $shop->owner }}</dd>

            <dt class="app-detail-list__term">Location</dt>
            <dd class="app-detail-list__desc">{{ $shop->latitude }}, {{ $shop->longitude }}</dd>

            <dt class="app-detail-list__term">Address</dt>
            <dd class="app-detail-list__desc app-detail-list__desc--multiline">{{ $shop->address }}</dd>
        </dl>
    </section>
@endsection