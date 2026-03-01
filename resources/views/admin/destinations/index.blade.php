@extends('admin.layouts.app')
@section('title', 'Destination')

@section('content')


<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Destination ({{ $destinations->count() }})</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Destination</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="content">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.destinations.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Add Destination
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="bg-light">
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Country</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($destinations as $dest)
            <tr>
                <td>
                    <img src="{{ $dest->image ? asset('storage/'.$dest->image) : 'https://placehold.co/50' }}" 
                         class="rounded shadow-sm" width="50" height="40" style="object-fit: cover;">
                </td>
                <td class="font-weight-bold">{{ $dest->name }}</td>
                <td><span class="badge badge-info">{{ $dest->country }}</span></td>
                <td>
                    @if($dest->is_featured)
                        <span class="text-success small"><i class="fas fa-star mr-1"></i> Featured</span>
                    @else
                        <span class="text-muted small">Standard</span>
                    @endif
                </td>
                <td class="text-right">
                    <div class="btn-group">
                        <a href="{{ route('admin.destinations.edit', $dest->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.destinations.destroy', $dest->id) }}" method="POST" onsubmit="return confirm('Delete this destination?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
                <!-- Pagination -->
                <div class="mt-3">
                    {{ $destinations->links() }}
                </div>
            </div>
        </div>
    </div>

</section>
@endsection
