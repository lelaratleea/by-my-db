@if($errors->any())
    <div class="app-cmp-notifications">
        @foreach($errors->all() as $error)
            <div role="alert" class="app-cmp-notifications__error">{{ $error }}</div>
        @endforeach
    </div>
@endif