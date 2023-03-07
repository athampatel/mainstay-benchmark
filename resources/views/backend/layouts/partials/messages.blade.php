@if ($errors->any())
    <div class="alert alert-danger bm-alert-danger">
        <div>
            @foreach ($errors->all() as $error)
                <p class="text-white">{{ $error }}</p>
            @endforeach
        </div>
    </div>
@endif

@if (Session::has('success'))
    <div class="alert alert-success bm-btn-primary">
        <div>
            <p>{{ Session::get('success') }}</p>
        </div>
    </div>
@endif

@if (Session::has('error'))
    <div class="alert alert-danger bm-alert-danger">
        <div>
            <p class="text-white">{{ Session::get('error') }}</p>
        </div>
    </div>
@endif