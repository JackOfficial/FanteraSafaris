@extends('admin.layouts.app')

@section('title', 'Posts')

@section('content')

{{-- ================= HEADER ================= --}}
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>
                    Posts
                    <small class="text-muted">({{ $posts->total() }} total posts)</small>
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Posts</li>
                </ol>
            </div>
        </div>
    </div>
</section>

{{-- ================= CONTENT ================= --}}
<section class="content">

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    {{-- ACTION BAR --}}
    <div class="mb-3 text-right">
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Post
        </a>
        <a href="{{ route('admin.posts.trash') }}" class="btn btn-danger btn-sm">
            <i class="fas fa-trash"></i> Trash
        </a>
    </div>

    {{-- POSTS TABLE --}}
    <div class="card card-outline card-secondary">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="thead-light">
                    <tr>
                        <th width="60">#</th>
                        <th width="90">Photo</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Published At</th>
                        <th width="160">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td>{{ $post->itelation }}</td>
                            <td>
    @if($post->featured_image)
        <img src="{{ asset('storage/'.$post->featured_image) }}"
             alt="Post Image"
             width="60"
             height="60"
             style="object-fit: cover; border-radius: 6px;">
    @else
        <span class="text-muted">—</span>
    @endif
</td>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->author->name ?? '—' }} {{ Auth::user()->name == $post->author->name ? ' (You)' : '' }}</td>
                            <td>{{ $post->category->name ?? '—' }}</td>
                            <td>
                                <span class="badge
                                    @if($post->status === 'draft') badge-secondary
                                    @elseif($post->status === 'published') badge-success
                                    @elseif($post->status === 'scheduled') badge-warning @endif">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </td>
                            <td>{{ $post->published_at?->format('d M Y H:i') ?? '—' }}</td>
                            <td>
                                <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this post?');">
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
                            <td colspan="7" class="text-center text-muted py-3">No posts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $posts->links() }}
    </div>

</section>
@endsection