@extends('admin.layouts.app')

@section('title', 'Trash - Posts')

@section('content')

{{-- ================= HEADER ================= --}}
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>
                    Trashed Posts
                    <small class="text-muted">({{ $posts->count() }} deleted)</small>
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Posts</a></li>
                    <li class="breadcrumb-item active">Trash</li>
                </ol>
            </div>
        </div>
    </div>
</section>

{{-- ================= CONTENT ================= --}}
<section class="content">
    <div class="container-fluid">

        {{-- FLASH MESSAGE --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        {{-- ACTIONS --}}
        <div class="mb-3 text-right">
            <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Posts
            </a>
        </div>

        {{-- TRASH TABLE --}}
        <div class="card card-outline card-danger">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-trash"></i> Deleted Posts</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Deleted At</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $post->title }}</td>
                                <td>{{ $post->category->name ?? '—' }}</td>
                                <td>{{ $post->author->name ?? '—' }}</td>
                                <td>{{ $post->deleted_at?->format('d M Y H:i') ?? '—' }}</td>
                                <td>
                                    {{-- Restore --}}
                                    <form action="{{ route('admin.posts.restore', $post->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-success btn-sm" onclick="return confirm('Restore this post?')">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>

                                    {{-- Permanent Delete --}}
                                    <form action="{{ route('admin.posts.forceDelete', $post->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Permanently delete this post?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">
                                    No deleted posts found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($posts->hasPages())
                <div class="card-footer">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>

    </div>
</section>

@endsection