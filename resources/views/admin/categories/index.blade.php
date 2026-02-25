@extends('admin.layouts.app')

@section('title', 'Blog Categories')

{{-- ================= HEADER ================= --}}
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>
                    Blog Categories
                    <small class="text-muted">({{ $categories->total() }} total)</small>
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Blog Categories</li>
                </ol>
            </div>
        </div>
    </div>
</section>

{{-- ================= CONTENT ================= --}}
<section class="content">

    {{-- Flash message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    {{-- Action bar --}}
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('admin.categories.trash') }}"
           class="btn btn-outline-danger btn-sm">
            <i class="fas fa-trash"></i> Trash
        </a>

        <a href="{{ route('admin.categories.create') }}"
           class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Category
        </a>
    </div>

    {{-- Categories Card --}}
    <div class="card card-outline card-primary">

        <div class="card-body p-0">

            <table class="table table-hover align-middle mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Created</th>
                        <th width="140" class="text-right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>
                                <strong>{{ $category->name }}</strong>
                                @if($category->description)
                                    <div class="text-muted small">
                                        {{ Str::limit($category->description, 80) }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                <span class="badge badge-secondary">
                                    {{ $category->slug }}
                                </span>
                            </td>

                            <td class="text-muted">
                                {{ $category->created_at->format('d M Y') }}
                            </td>

                            <td class="text-right">
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.categories.destroy', $category) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Move this category to trash?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center p-4 text-muted">
                                <i class="fas fa-folder-open fa-2x mb-2"></i>
                                <div>No categories found.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($categories->hasPages())
        <div class="mt-3">
            {{ $categories->links() }}
        </div>
    @endif

</section>
@endsection