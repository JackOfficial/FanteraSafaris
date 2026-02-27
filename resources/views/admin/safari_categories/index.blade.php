@extends('admin.layouts.app')

@section('title', 'Safari Categories')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="font-weight-bold">Safari Categories</h1>
            </div>
            <div class="col-sm-6 text-right">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Categories</li>
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
                    <i class="fas fa-tags mr-2 text-pink"></i> Category Management
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.safari-categories.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                        <i class="fas fa-plus mr-1"></i> Add New Category
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
                            <th class="px-4 border-0">Category</th>
                            <th class="border-0">Slug</th>
                            <th class="border-0 text-center">Packages</th>
                            <th class="text-right px-4 border-0">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        {{-- Polymorphic Photo for Category --}}
                                        @php $photo = $category->photo; @endphp
                                        <div class="mr-3">
                                            @if($photo)
                                                <img src="{{ asset('storage/' . $photo->path) }}" 
                                                     class="rounded shadow-sm border" 
                                                     style="width: 45px; height: 45px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center border" style="width: 45px; height: 45px;">
                                                    <i class="fas fa-folder text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-weight-bold text-dark d-block">{{ $category->name }}</span>
                                            <small class="text-muted">ID: #{{ $category->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code class="text-pink bg-light px-2 py-1 rounded small">{{ $category->slug }}</code>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-pill badge-light border px-3">
                                        {{ $category->safari_packages_count ?? $category->safariPackages()->count() }}
                                    </span>
                                </td>
                                <td class="text-right px-4">
                                    <div class="btn-group shadow-sm rounded">
                                        <a href="{{ route('admin.safari-categories.edit', $category) }}" class="btn btn-sm btn-white border" title="Edit">
                                            <i class="fas fa-edit text-info"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-white border text-danger" 
                                                onclick="if(confirm('Delete this category? This might affect existing packages.')) document.getElementById('delete-cat-{{ $category->id }}').submit();">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="delete-cat-{{ $category->id }}" action="{{ route('admin.safari-categories.destroy', $category) }}" method="POST" class="d-none">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <p class="text-muted">No categories created yet.</p>
                                    <a href="{{ route('admin.safari-categories.create') }}" class="btn btn-primary btn-sm">Create First Category</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($categories->hasPages())
                <div class="card-footer bg-white border-top-0 d-flex justify-content-center">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection