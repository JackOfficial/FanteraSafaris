@extends('admin.layouts.app')

@section('title', 'Safari Packages')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="font-weight-bold">Safari Packages</h1>
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
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <h3 class="card-title font-weight-bold mb-0">
                    <i class="fas fa-map-marked-alt mr-2 text-pink"></i> All Safari Packages
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.packages.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                        <i class="fas fa-plus mr-1"></i> Add New Package
                    </a>
                </div>
            </div>

            <div class="card-body p-0 table-responsive">
                @if(session('success'))
                    <div class="alert alert-success m-3 alert-dismissible fade show border-0 shadow-sm">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 border-0">Package</th>
                            <th class="border-0">Destination</th> {{-- Added Column --}}
                            <th class="border-0">Category</th>
                            <th class="border-0">Price</th>
                            <th class="border-0">Duration</th>
                            <th class="border-0 text-center">Status</th>
                            <th class="text-right px-4 border-0">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $package)
                            <tr>
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        @php $featured = $package->photos->firstWhere('type', 'featured'); @endphp
                                        <div class="mr-3">
                                            @if($featured)
                                                <img src="{{ asset('storage/' . $featured->path) }}" 
                                                     alt="Safari Image" class="rounded shadow-sm border" 
                                                     style="width: 45px; height: 45px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center border" style="width: 45px; height: 45px;">
                                                    <i class="fas fa-camera text-muted small"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark">{{ $package->name }}</div>
                                            <small class="text-muted text-uppercase" style="font-size: 10px; letter-spacing: 0.5px;">
                                                ID: #SAF-{{ str_pad($package->id, 4, '0', STR_PAD_LEFT) }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{-- Showing Destination from Relationship --}}
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-location-arrow text-muted mr-2 small"></i>
                                        <span class="text-dark font-weight-medium">
                                            {{ $package->destination->name ?? 'Global' }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-light border text-secondary px-2 py-1">
                                        {{ $package->category->name ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-dark font-weight-bold">
                                        {{ number_format($package->price) }} <small>USD</small>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted small">
                                        <i class="far fa-calendar-alt mr-1"></i> {{ $package->duration_days }} Days
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($package->status === 'published')
                                        <span class="badge badge-success shadow-xs px-2 py-1" style="font-size: 0.7rem; border-radius: 4px;">PUBLISHED</span>
                                    @else
                                        <span class="badge badge-warning shadow-xs px-2 py-1" style="font-size: 0.7rem; border-radius: 4px;">DRAFT</span>
                                    @endif
                                </td>
                                <td class="text-right px-4">
                                    <div class="btn-group shadow-sm">
                                        <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-sm btn-white border" title="Edit">
                                            <i class="fas fa-edit text-info"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-white border text-danger" 
                                                onclick="if(confirm('Are you sure you want to delete this package?')) document.getElementById('delete-{{ $package->id }}').submit();">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <form id="delete-{{ $package->id }}" action="{{ route('admin.packages.destroy', $package) }}" method="POST" class="d-none">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fas fa-map-marked-alt fa-3x text-light mb-3"></i>
                                        <p class="text-muted">No safari packages found matching your criteria.</p>
                                        <a href="{{ route('admin.packages.create') }}" class="btn btn-primary btn-sm px-4 rounded-pill">Create First Package</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($packages->hasPages())
                <div class="card-footer bg-white border-top d-flex justify-content-center">
                    {{ $packages->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection