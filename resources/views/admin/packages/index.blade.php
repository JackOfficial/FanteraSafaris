@extends('admin.layouts.app')

@section('title', 'Safari Packages')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Safari Packages</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Packages</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-map-marked-alt mr-2 text-pink"></i> All Safari Packages
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.packages.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Add New Package
                    </a>
                </div>
            </div>

            <div class="card-body p-0 table-responsive">
                @if(session('success'))
                    <div class="alert alert-success m-3 alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4">Package Name</th>
                            <th>Price</th>
                            <th>Duration</th>
                            <th class="text-right px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $package)
                            <tr>
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3 bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-suitcase text-muted"></i>
                                        </div>
                                        <strong>{{ $package->name }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-success font-weight-bold">
                                        ${{ number_format($package->price) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-outline-secondary">
                                        <i class="far fa-clock mr-1"></i> {{ $package->duration_days }} Days
                                    </span>
                                </td>
                                <td class="text-right px-4">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-sm btn-info text-white" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" class="d-inline">
                                            @csrf 
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this safari package?')" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <p class="text-muted">No safari packages found.</p>
                                    <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">Create your first package</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($packages->hasPages())
                <div class="card-footer bg-white border-top-0">
                    {{ $packages->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection