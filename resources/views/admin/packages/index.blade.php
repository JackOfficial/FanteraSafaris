@extends('admin.layouts.app')

@section('title', 'Safari Packages')

@push('styles')
<style>
    /* Modern UI Refinements */
    .table thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        font-weight: 700;
        border-top: none !important;
    }
    .package-row { transition: all 0.2s; }
    .package-row:hover { background-color: #fcfcfc !important; }
    
    .status-pill {
        font-size: 10px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 50px;
        letter-spacing: 0.5px;
    }
    .price-text {
        font-family: 'Inter', -apple-system, sans-serif;
        color: #2c3e50;
        font-weight: 700;
    }
    .dest-badge {
        background: #fdf2f7;
        color: #e83e8c;
        border-radius: 4px;
        padding: 2px 6px;
        font-size: 11px;
    }
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="m-0 font-weight-bold">Safari Packages</h3>
                <p class="text-muted small mb-0">Manage your tour inventory and pricing.</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.packages.create') }}" class="btn btn-primary shadow-sm px-4 rounded-pill">
                    <i class="fas fa-plus-circle mr-2"></i>Create New Safari
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content" x-data="{ 
    search: '',
    showRow(name, meta) {
        if (this.search === '') return true;
        const term = this.search.toLowerCase();
        return name.toLowerCase().includes(term) || meta.toLowerCase().includes(term);
    }
}">
    <div class="container-fluid">
        {{-- Smart Stats Summary --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0 bg-white p-3">
                    <small class="text-muted font-weight-bold">TOTAL PACKAGES</small>
                    <h4 class="mb-0">{{ $packages->total() }}</h4>
                </div>
            </div>
            {{-- Add more stat cards here if needed --}}
        </div>

        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="input-group input-group-sm rounded-pill border p-1" style="background: #f8f9fa;">
                            <div class="input-group-prepend border-0">
                                <span class="input-group-text bg-transparent border-0"><i class="fas fa-search text-muted"></i></span>
                            </div>
                            <input type="text" x-model="search" class="form-control border-0 bg-transparent shadow-none" placeholder="Quick find by name or destination...">
                        </div>
                    </div>
                    <div class="col-md-8 text-right">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-light border dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-filter mr-1"></i> Bulk Actions
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i class="fas fa-eye mr-2"></i>Publish Selected</a>
                                <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash mr-2"></i>Delete Selected</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0 table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="px-4">Service Details</th>
                            <th>Route / Locations</th>
                            <th>Pricing (USD)</th>
                            <th>Duration</th>
                            <th class="text-center">Status</th>
                            <th class="text-right px-4">Management</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $package)
                            @php 
                                $destString = $package->destinations->pluck('name')->implode(' ');
                                $catString = $package->categories->pluck('name')->implode(' ');
                                $featured = $package->photos->firstWhere('type', 'featured'); 
                            @endphp
                            <tr class="package-row" 
                                x-show="showRow('{{ addslashes($package->name) }}', '{{ addslashes($destString . ' ' . $catString) }}')"
                                x-transition>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative">
                                            @if($featured)
                                                <img src="{{ asset('storage/' . $featured->path) }}" class="rounded shadow-sm" style="width: 52px; height: 52px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; border: 2px dashed #dee2e6;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <div class="font-weight-bold text-dark h6 mb-0">{{ $package->name }}</div>
                                            <div class="d-flex align-items-center mt-1">
                                                @foreach($package->categories as $cat)
                                                    <span class="text-muted small mr-2"><i class="fas fa-tag mr-1" style="font-size: 9px;"></i>{{ $cat->name }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap" style="gap: 5px;">
                                        @foreach($package->destinations as $dest)
                                            <span class="dest-badge font-weight-bold">{{ $dest->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <span class="price-text h6 mb-0">${{ number_format($package->price) }}</span>
                                    <div class="text-muted small">base rate</div>
                                </td>
                                <td>
                                    <div class="text-dark font-weight-bold mb-0">{{ $package->duration_days }} Days</div>
                                    <div class="text-muted small">{{ $package->itineraries_count ?? $package->itineraries->count() }} stops</div>
                                </td>
                                <td class="text-center">
                                    @if($package->status === 'published')
                                        <span class="status-pill bg-success-light text-success border border-success" style="background: #e6fffa;">PUBLISHED</span>
                                    @else
                                        <span class="status-pill bg-warning-light text-warning border border-warning" style="background: #fffbef;">DRAFT</span>
                                    @endif
                                </td>
                                <td class="text-right px-4">
                                    <div class="btn-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                        <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-white btn-sm px-3" title="Edit Package">
                                            <i class="fas fa-edit text-primary"></i>
                                        </a>
                                        <a href="#" class="btn btn-white btn-sm px-3" title="View on Site">
                                            <i class="fas fa-external-link-alt text-muted"></i>
                                        </a>
                                        <button type="button" class="btn btn-white btn-sm px-3" 
                                                onclick="confirmDelete('{{ $package->id }}')" title="Delete">
                                            <i class="fas fa-trash-alt text-danger"></i>
                                        </button>
                                    </div>
                                    <form id="delete-{{ $package->id }}" action="{{ route('admin.packages.destroy', $package) }}" method="POST" class="d-none">
                                        @csrf @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fas fa-map-marked-alt fa-3x text-light mb-3"></i>
                                        <h5 class="text-muted">No Safari Packages Found</h5>
                                        <p class="text-muted small">Click the "Create New Safari" button to get started.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white border-top py-3" x-show="search === ''">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <p class="small text-muted mb-0">Displaying {{ $packages->firstItem() }} to {{ $packages->lastItem() }} of {{ $packages->total() }} results</p>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-right">
                            {{ $packages->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Are you sure? This will permanently delete the package and its itinerary data.')) {
            document.getElementById('delete-' + id).submit();
        }
    }
</script>
@endpush
@endsection