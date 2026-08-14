@extends('shops.main', [
    'title' => $shop->name,
])

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