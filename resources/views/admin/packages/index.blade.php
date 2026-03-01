@extends('admin.layouts.app')

@section('title', 'Safari Packages')

@push('styles')
<style>
    /* Modern UI Refinements */
    .table thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.72rem;
        letter-spacing: 0.05em;
        font-weight: 700;
        border-top: none !important;
        border-bottom: 2px solid #eee !important;
        vertical-align: middle;
    }
    .package-row { transition: all 0.2s; border-bottom: 1px solid #f1f1f1; }
    .package-row:hover { background-color: #fcfcfc !important; transform: translateY(-1px); }
    
    .status-pill {
        font-size: 10px;
        font-weight: 800;
        padding: 4px 12px;
        border-radius: 50px;
        letter-spacing: 0.5px;
        display: inline-block;
    }
    .price-text {
        font-family: 'Inter', -apple-system, sans-serif;
        color: #2c3e50;
        font-weight: 800;
        font-size: 1.1rem;
    }
    .discount-badge {
        background: #fff5f5;
        color: #e53e3e;
        border: 1px solid #feb2b2;
        border-radius: 4px;
        padding: 0px 4px;
        font-size: 9px;
        font-weight: 700;
        margin-left: 4px;
        vertical-align: middle;
    }
    .dest-badge {
        background: #fff0f6;
        color: #e83e8c;
        border: 1px solid #ffdeeb;
        border-radius: 4px;
        padding: 1px 6px;
        font-size: 11px;
        font-weight: 600;
    }
    .btn-white { background: #fff; border: 1px solid #dee2e6; color: #444; }
    .btn-white:hover { background: #f8f9fa; color: #000; }
    .text-pink { color: #e83e8c !important; }
    .bg-pink { background-color: #e83e8c !important; color: white; }
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="m-0 font-weight-bold text-dark">Safari Packages</h1>
                <p class="text-muted small mb-0">You have a total of <strong>{{ $packages->total() }}</strong> active packages.</p>
            </div>
            <div class="d-flex align-items-center" style="gap: 10px;">
                <a href="{{ route('admin.packages.export.pdf') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm">
                    <i class="fas fa-file-pdf mr-1"></i> Download PDF
                </a>
                <a href="{{ route('admin.packages.create') }}" class="btn bg-pink btn-sm rounded-pill px-4 shadow-sm">
                    <i class="fas fa-plus-circle mr-1"></i> Add New Package
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
        
        <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-white py-3 border-0">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="input-group shadow-none border rounded-pill px-2" style="background: #f8f9fa;">
                            <div class="input-group-prepend border-0">
                                <span class="input-group-text bg-transparent border-0"><i class="fas fa-search text-muted"></i></span>
                            </div>
                            <input type="text" x-model="search" class="form-control border-0 bg-transparent shadow-none" placeholder="Search by name, category, or park...">
                        </div>
                    </div>
                    <div class="col-md-8 text-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-white dropdown-toggle rounded-pill px-3" data-toggle="dropdown">
                                <i class="fas fa-cog mr-1 text-muted"></i> Bulk Actions
                            </button>
                            <div class="dropdown-menu dropdown-menu-right border-0 shadow">
                                <a class="dropdown-item" href="#"><i class="fas fa-check text-success mr-2"></i>Publish Selected</a>
                                <a class="dropdown-item" href="#"><i class="fas fa-file-excel text-success mr-2"></i>Export to Excel</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash mr-2"></i>Delete Selected</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0 table-responsive">
                @if(session('success'))
                    <div class="alert alert-success mx-4 mt-3 border-0 shadow-sm alert-dismissible fade show">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="px-4">Service Details</th>
                            <th>Destinations</th>
                            <th>Pricing (USD)</th>
                            <th>Duration</th>
                            <th class="text-center">Status</th>
                            <th class="text-right px-4">Actions</th>
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
                                                <img src="{{ asset('storage/' . $featured->path) }}" class="rounded shadow-sm border" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center border" style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted opacity-50"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <div class="font-weight-bold text-dark mb-0" style="font-size: 0.95rem;">{{ $package->name }}</div>
                                            <div class="d-flex align-items-center">
                                                @foreach($package->categories as $cat)
                                                    <span class="text-muted" style="font-size: 11px;">
                                                        <i class="fas fa-tag mr-1 text-pink" style="font-size: 8px;"></i>{{ $cat->name }}{{ !$loop->last ? ' • ' : '' }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap" style="gap: 4px; max-width: 220px;">
                                        @foreach($package->destinations as $dest)
                                            <span class="dest-badge">{{ $dest->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="price-text">${{ number_format($package->price) }}</span>
                                        @if($package->discount_rate > 0)
                                            <span class="discount-badge">-{{ number_format($package->discount_rate, 0) }}%</span>
                                        @endif
                                    </div>
                                    <div class="text-muted small" style="font-size: 10px; margin-top: -4px;">
                                        @if($package->discount_rate > 0)
                                            <span class="text-success font-weight-bold">Now ${{ number_format($package->price * (1 - ($package->discount_rate / 100)), 2) }}</span>
                                        @else
                                            Per person
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="text-dark font-weight-bold mb-0">{{ $package->duration_days }} Days</div>
                                    <div class="text-muted small" style="font-size: 11px;">{{ count($package->itineraries) }} daily stops</div>
                                </td>
                                <td class="text-center">
                                    @if($package->status === 'published')
                                        <span class="status-pill border border-success text-success" style="background: #f0fff4;">PUBLISHED</span>
                                    @else
                                        <span class="status-pill border border-warning text-warning" style="background: #fffaf0;">DRAFT</span>
                                    @endif
                                </td>
                                <td class="text-right px-4">
                                    <div class="btn-group shadow-sm" style="border-radius: 6px; overflow: hidden;">
                                        <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-white btn-sm px-3" title="Edit">
                                            <i class="fas fa-pencil-alt text-primary"></i>
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
                                    <div class="py-4 text-muted">
                                        <i class="fas fa-search fa-3x mb-3 opacity-25"></i>
                                        <h5>No Packages Found</h5>
                                        <p class="small">Try adjusting your filters or add a new safari package.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white border-top py-3" x-show="search === ''">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small text-muted font-italic">
                        Showing {{ $packages->firstItem() ?? 0 }} to {{ $packages->lastItem() ?? 0 }} of {{ $packages->total() }} results
                    </span>
                    <div>
                        {{ $packages->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Are you sure? This will remove the package and all its itinerary data from the system.')) {
            document.getElementById('delete-' + id).submit();
        }
    }
</script>
@endpush
@endsection