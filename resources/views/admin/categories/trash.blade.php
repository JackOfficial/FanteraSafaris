@extends('admin.layouts.app')

@section('title', 'Trashed Categories | FameOceans')

@section('content')
<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Trashed Categories</h1>

        <a href="{{ route('admin.categories.index') }}"
           class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Categories
        </a>
    </div>

    <!-- Flash Message -->
    @if (session('success'))
        <div class="alert alert-success small">
            {{ session('success') }}
        </div>
    @endif

    <!-- Trash Table -->
    <div class="card card-outline card-danger">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-trash"></i> Deleted Categories
            </h3>
        </div>

        <div class="card-body p-0">

            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Deleted At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td class="text-muted">{{ $category->slug }}</td>
                        <td>{{ $category->deleted_at->format('d M Y, H:i') }}</td>

                        <td class="text-center">
                            <div class="btn-group">

                                <!-- Restore -->
                                <form method="POST"
                                      action="{{ route('admin.categories.restore', $category->id) }}">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-sm btn-success"
                                            title="Restore">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </form>

                                <!-- Permanent Delete -->
                                <form method="POST"
                                      action="{{ route('admin.categories.force-delete', $category->id) }}"
                                      onsubmit="return confirm('This action is permanent. Continue?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-danger"
                                            title="Delete Permanently">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4"
                            class="text-center text-muted py-4">
                            Trash is empty 🎉
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

        </div>

        @if ($categories->hasPages())
            <div class="card-footer clearfix">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

</div>
@endsection