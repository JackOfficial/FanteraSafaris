{{-- Success Message --}}
@if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show m-3 border-0 shadow-sm" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle mr-2 fa-lg"></i>
            <div>
                <strong>Success!</strong> {{ session('success') }}
            </div>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

{{-- Error Message --}}
@if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show m-3 border-0 shadow-sm" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle mr-2 fa-lg"></i>
            <div>
                <strong>Hold on!</strong> {{ session('error') }}
            </div>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

{{-- Validation Errors (Optional but helpful for modals) --}}
@if ($errors->any())
    <div class="alert alert-warning alert-dismissible fade show m-3 border-0 shadow-sm" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif