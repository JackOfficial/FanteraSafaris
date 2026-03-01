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

<section class="content" x-data="{ 
    search: '',
    showRow(name, destination) {
        if (this.search === '') return true;
        const term = this.search.toLowerCase();
        return name.toLowerCase().includes(term) || destination.toLowerCase().includes(term);
    }
}">
    <div class="container-fluid">
        {{-- Action Bar --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 gap-2">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <div class="input-group shadow-sm" style="width: 300px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white border-right-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                    </div>
                    <input type="text" 
                           x-model="search" 
                           class="form-control border-left-0 shadow-none" 
                           placeholder="Live search packages...">
                </div>
            </div>

            <div class="d-flex align-items-center">
                <div class="btn-group mr-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle rounded-pill px-3" data-toggle="dropdown">
                        <i class="fas fa-download mr-1"></i> Export
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow border-0">
                        <a class="dropdown-item" href="{{ route('admin.packages.export.pdf') }}">
                            <i class="fas fa-file-pdf text-danger mr-2"></i> Download PDF
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-file-excel text-success mr-2"></i> Export Excel
                        </a>
                    </div>
                </div>
                <a href="{{ route('admin.packages.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Add New Package
                </a>
            </div>
        </div>

        <div class="card shadow-sm border-0">
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
                            <th class="border-0">Destination</th>
                            <th class="border-0">Category</th>
                            <th class="border-0">Price</th>
                            <th class="border-0">Duration</th>
                            <th class="border-0 text-center">Status</th>
                            <th class="text-right px-4 border-0">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $package)
                            <tr x-show="showRow('{{ addslashes($package->name) }}', '{{ addslashes($package->destination->name ?? '') }}')"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100">
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        @php $featured = $package->photos->firstWhere('type', 'featured'); @endphp
                                        <div class="mr-3">
                                            @if($featured)
                                                <img src="{{ asset('storage/' . $featured->path) }}" 
                                                     class="rounded shadow-sm border" 
                                                     style="width: 45px; height: 45px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center border" style="width: 45px; height: 45px;">
                                                    <i class="fas fa-camera text-muted small"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark">{{ $package->name }}</div>
                                            <small class="text-muted">#{{ $package->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-dark font-weight-medium">
                                        <i class="fas fa-map-marker-alt text-muted mr-1 small"></i>
                                        {{ $package->destination->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-light border text-secondary px-2 py-1">
                                        {{ $package->category->name ?? 'General' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-dark font-weight-bold">
                                        ${{ number_format($package->price) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted small">
                                        {{ $package->duration_days }} Days
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $package->status === 'published' ? 'badge-success' : 'badge-warning' }} px-2 py-1" style="font-size: 0.7rem;">
                                        {{ strtoupper($package->status) }}
                                    </span>
                                </td>
                                <td class="text-right px-4">
                                    <div class="btn-group shadow-sm">
                                        <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-sm btn-white border">
                                            <i class="fas fa-edit text-info"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-white border text-danger" 
                                                onclick="if(confirm('Delete?')) document.getElementById('delete-{{ $package->id }}').submit();">
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
                                    <p class="text-muted">No packages found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Hide pagination when searching --}}
            <div class="card-footer bg-white border-top" x-show="search === ''">
                @if($packages->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $packages->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection