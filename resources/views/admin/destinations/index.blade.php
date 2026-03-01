@extends('admin.layouts.app')
@section('title', 'Manage Destinations')

@section('content')
<div x-data="{ 
    selectedIds: [], 
    allIds: {{ $destinations->pluck('id')->toJson() }},
    toggleAll() {
        this.selectedIds = this.selectedIds.length === this.allIds.length ? [] : [...this.allIds];
    }
}">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-4 align-items-end">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold" style="letter-spacing: -1px;">Destinations 
                        <span class="badge badge-pill badge-soft-dark ml-2" style="font-size: 0.4em; vertical-align: middle; background: #eee;">
                            {{ $destinations->total() }} Total
                        </span>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right bg-transparent p-0 small">
                        <li class="breadcrumb-item"><a href="/admin" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item active text-warning">Destinations</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            
            @include('admin.partials.alerts') {{-- Assuming you use the custom alerts --}}

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <a href="{{ route('admin.destinations.create') }}" class="btn btn-warning shadow-sm px-4 font-weight-bold">
                        <i class="fa fa-plus-circle mr-1"></i> Add Destination
                    </a>

                    <div x-show="selectedIds.length > 0" x-transition.fade class="ml-3">
                        <form action="{{ route('admin.destinations.bulk-delete') }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete these selected destinations?')">
                            @csrf @method('DELETE')
                            <input type="hidden" name="ids" :value="selectedIds.join(',')">
                            <button type="submit" class="btn btn-outline-danger btn-sm border-0">
                                <i class="fas fa-trash-alt mr-1"></i> Delete Selected (<span x-text="selectedIds.length"></span>)
                            </button>
                        </form>
                    </div>
                </div>

                <div class="search-box">
                    <input type="text" class="form-control form-control-sm border-0 shadow-sm" placeholder="Search destination..." style="border-radius: 20px; width: 250px;">
                </div>
            </div>

            <div class="card shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small uppercase">
                                <tr>
                                    <th width="40" class="text-center pl-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkAll" 
                                                   @click="toggleAll()" :checked="selectedIds.length === allIds.length && allIds.length > 0">
                                            <label class="custom-control-label" for="checkAll"></label>
                                        </div>
                                    </th>
                                    <th>Location Info</th>
                                    <th>Country</th>
                                    <th class="text-center">Visibility</th>
                                    <th class="text-right pr-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($destinations as $dest)
                                <tr :class="selectedIds.includes({{ $dest->id }}) ? 'bg-light-warning' : ''" style="transition: all 0.2s;">
                                    <td class="text-center pl-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="check-{{ $dest->id }}" 
                                                   value="{{ $dest->id }}" x-model.number="selectedIds">
                                            <label class="custom-control-label" for="check-{{ $dest->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $dest->image ? asset('storage/'.$dest->image) : 'https://placehold.co/100x100?text=No+Image' }}" 
                                                 class="rounded mr-3 shadow-sm" width="60" height="45" style="object-fit: cover; border: 2px solid #fff;">
                                            <div>
                                                <div class="font-weight-bold text-dark">{{ $dest->name }}</div>
                                                <small class="text-muted">Slug: {{ $dest->slug }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-light px-3 py-2 text-uppercase" style="letter-spacing: 0.5px; border: 1px solid #ddd;">
                                            <i class="fas fa-map-marker-alt text-warning mr-1"></i> {{ $dest->country }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($dest->is_featured)
                                            <span class="badge badge-success-soft text-success px-3">
                                                <i class="fas fa-star mr-1"></i> Featured
                                            </span>
                                        @else
                                            <span class="badge badge-light text-muted px-3 border">Hidden</span>
                                        @endif
                                    </td>
                                    <td class="text-right pr-4">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.destinations.edit', $dest->id) }}" class="btn btn-sm btn-white shadow-sm border" title="Edit">
                                                <i class="fas fa-pen text-primary"></i>
                                            </a>
                                            <form action="{{ route('admin.destinations.destroy', $dest->id) }}" method="POST" 
                                                  class="d-inline" onsubmit="return confirm('Delete this destination?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-white shadow-sm border" title="Delete">
                                                    <i class="fas fa-trash text-danger"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-map-marked fa-3x mb-3 d-block opacity-50"></i>
                                        No destinations found. <a href="{{ route('admin.destinations.create') }}">Create your first one!</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="small text-muted mb-0">Showing {{ $destinations->firstItem() }} to {{ $destinations->lastItem() }} of {{ $destinations->total() }} destinations</p>
                        <div>{{ $destinations->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    /* Professional Soft Badge Styling */
    .badge-success-soft { background-color: #e8f5e9; color: #2e7d32; }
    .bg-light-warning { background-color: #fffdf5 !important; }
    .btn-white { background: #fff; }
    .btn-white:hover { background: #f8f9fa; }
    .pagination { margin-bottom: 0; }
    .page-link { border: none; color: #333; margin: 0 2px; border-radius: 5px !important; }
    .page-item.active .page-link { background-color: #ffc107; color: #000; font-weight: bold; }
</style>
@endsection