@extends('admin.layouts.app')

@section('title', 'Edit Post')

@section('content')
<section class="content">
    <div class="container-fluid">

        {{-- ================= HEADER / Breadcrumb ================= --}}
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1>Edit Post</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Posts</a></li>
                    <li class="breadcrumb-item active">Edit Post</li>
                </ol>
            </div>
        </div>

        {{-- FLASH MESSAGE --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        {{-- ================= FORM CARD ================= --}}
        <div class="card card-outline card-primary">
            <div class="card-body">
                <form action="{{ route('admin.posts.update', $post) }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    {{-- ================= POST DETAILS ================= --}}
                    <fieldset class="border rounded p-3 mb-4">
                        <legend class="w-auto px-2 text-primary">
                            <i class="fas fa-pencil-alt"></i> Post Details
                        </legend>

                        {{-- Title --}}
                        <div class="form-group mb-3">
                            <label><i class="fas fa-heading"></i> Title *</label>
                            <input type="text" name="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $post->title) }}" required>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Excerpt --}}
                        <div class="form-group mb-3">
                            <label><i class="fas fa-align-left"></i> Excerpt</label>
                            <textarea name="excerpt" class="form-control" rows="3">{{ old('excerpt', $post->excerpt) }}</textarea>
                        </div>

                        {{-- Category --}}
                        <div class="form-group mb-3">
                            <label><i class="fas fa-folder-open"></i> Category</label>
                            <select name="blog_category_id" class="form-control">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('blog_category_id', $post->blog_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Featured Image --}}
                        <div class="form-group mb-3" x-data="{ preview: '{{ $post->featured_image ? asset('storage/'.$post->featured_image) : '' }}' }">
                            <label><i class="fas fa-image"></i> Featured Image</label>
                            <input type="file" name="featured_image" class="form-control-file"
                                   @change="preview = URL.createObjectURL($event.target.files[0])">

                            <template x-if="preview">
                                <img :src="preview" class="img-thumbnail mt-2" style="width:120px;">
                            </template>

                            @error('featured_image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Content --}}
                        <div class="form-group mb-0">
                            <label><i class="fas fa-file-alt"></i> Content *</label>
                            <textarea id="myeditorinstance"
                                      name="content"
                                      class="form-control @error('content') is-invalid @enderror"
                                      rows="8" required>{{ old('content', $post->content) }}</textarea>
                            @error('content')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </fieldset>

                    {{-- ================= PUBLISHING ================= --}}
                    <fieldset class="border rounded p-3 mb-4">
                        <legend class="w-auto px-2 text-warning">
                            <i class="fas fa-calendar-alt"></i> Publishing
                        </legend>

                        <div class="form-group mb-3">
                            <label><i class="fas fa-toggle-on"></i> Status</label>
                            <select name="status" class="form-control">
                                <option value="draft" {{ old('status', $post->status)=='draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $post->status)=='published' ? 'selected' : '' }}>Published</option>
                                <option value="scheduled" {{ old('status', $post->status)=='scheduled' ? 'selected' : '' }}>Scheduled</option>
                            </select>
                        </div>

                        <div class="form-group mb-0">
                            <label><i class="fas fa-clock"></i> Publish At</label>
                            <input type="datetime-local"
                                   name="published_at"
                                   class="form-control"
                                   value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}">
                        </div>
                    </fieldset>

                    {{-- ================= SUBMIT ================= --}}
                    <div class="text-right">
                        <button class="btn btn-success" type="submit">
                            <i class="fas fa-save"></i> Update Post
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

{{-- ================= TINYMCE SIMPLE ================= --}}
<script src="https://cdn.tiny.cloud/1/akrlpx2p28jit8egdeyeyu4q0osghccndfvgksphc8hfni44/tinymce/8/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#myeditorinstance',
    plugins: 'lists link image table code wordcount',
    toolbar: 'undo redo | styles | blocks | bold italic | alignleft aligncenter alignright alignjustify | indent outdent | bullist numlist | code | table'
});
</script>

@endsection