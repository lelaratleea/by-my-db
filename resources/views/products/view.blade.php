@extends('products.main', [
    'title' => $product->name,
])

@section('content')
    <section class="app-detail-section">
        <dl class="app-detail-list">
            <dt class="app-detail-list__term">Code</dt>
            <dd class="app-detail-list__desc">{{ $product->code }}</dd>

            <dt class="app-detail-list__term">Name</dt>
            <dd class="app-detail-list__desc">{{ $product->name }}</dd>

            <dt class="app-detail-list__term">Price</dt>
            <dd class="app-detail-list__desc">{{ number_format((float)$product->price, 2) }}</dd>
        </dl>
        <pre class="app-detail-description">{{ $product->description }}</pre>
    </section>
@endsection