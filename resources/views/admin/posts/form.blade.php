@csrf

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
               value="{{ old('title', $post->title ?? '') }}" required>
        @error('title') 
            <span class="invalid-feedback">{{ $message }}</span> 
        @enderror
    </div>

    {{-- Excerpt --}}
    <div class="form-group mb-3">
        <label><i class="fas fa-align-left"></i> Excerpt</label>
        <textarea name="excerpt" class="form-control @error('excerpt') is-invalid @enderror" rows="3">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
        @error('excerpt')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    {{-- Category --}}
    <div class="form-group mb-3">
        <label><i class="fas fa-folder-open"></i> Category</label>
        <select name="blog_category_id" class="form-control @error('blog_category_id') is-invalid @enderror">
            <option value="">Select Category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('blog_category_id', $post->blog_category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('blog_category_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    {{-- Featured Image --}}
    <div class="form-group mb-3" x-data="{ photoName: '', photoPreview: '{{ isset($post->featured_image) ? asset('storage/'.$post->featured_image) : '' }}' }">
        <label><i class="fas fa-image"></i> Featured Image</label>
        <input type="file" name="featured_image" 
               class="form-control-file @error('featured_image') is-invalid @enderror"
               x-on:change="
                    photoName = $event.target.files[0].name;
                    const reader = new FileReader();
                    reader.onload = e => photoPreview = e.target.result;
                    reader.readAsDataURL($event.target.files[0]);
               ">
        @error('featured_image')
            <span class="text-danger">{{ $message }}</span>
        @enderror

        <template x-if="photoPreview">
            <img :src="photoPreview" class="img-thumbnail mt-2" style="width:150px; height:auto;">
        </template>
    </div>

    {{-- Content --}}
<div class="form-group mb-0" x-data x-init="$nextTick(() => $refs.editor && Alpine.store('tinymceEditor').init($refs.editor, '{{ old('content', addslashes($post->content ?? '')) }}'))">
    <label><i class="fas fa-file-alt"></i> Content *</label>

    <textarea 
        x-ref="editor"
        name="content" 
        class="form-control @error('content') is-invalid @enderror" 
        rows="8" 
        required
    >{{ old('content', $post->content ?? '') }}</textarea>

    @error('content') 
        <span class="text-danger">{{ $message }}</span> 
    @enderror
</div>

</fieldset>

{{-- ================= SEO SETTINGS ================= --}}
<fieldset class="border rounded p-3 mb-4">
    <legend class="w-auto px-2 text-success"><i class="fas fa-search"></i> SEO Settings</legend>

    <div class="form-group mb-3">
        <label><i class="fas fa-heading"></i> Meta Title</label>
        <input type="text" name="meta_title" 
               class="form-control @error('meta_title') is-invalid @enderror" 
               value="{{ old('meta_title', $post->meta_title ?? '') }}">
        @error('meta_title')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group mb-0">
        <label><i class="fas fa-align-justify"></i> Meta Description</label>
        <textarea name="meta_description" 
                  class="form-control @error('meta_description') is-invalid @enderror" 
                  rows="2">{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
        @error('meta_description')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</fieldset>

{{-- ================= PUBLISHING ================= --}}
<fieldset class="border rounded p-3 mb-4">
    <legend class="w-auto px-2 text-warning"><i class="fas fa-calendar-alt"></i> Publishing</legend>

    <div class="form-group mb-3">
        <label><i class="fas fa-toggle-on"></i> Status</label>
        <select name="status" class="form-control @error('status') is-invalid @enderror">
            @php $status = old('status', $post->status ?? 'draft'); @endphp
            <option value="draft" {{ $status=='draft' ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ $status=='published' ? 'selected' : '' }}>Published</option>
            <option value="scheduled" {{ $status=='scheduled' ? 'selected' : '' }}>Scheduled</option>
        </select>
        @error('status')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group mb-0">
        <label><i class="fas fa-clock"></i> Publish At</label>
        <input type="datetime-local" name="published_at" 
               class="form-control @error('published_at') is-invalid @enderror"
               value="{{ old('published_at', isset($post->published_at) ? $post->published_at->format('Y-m-d\TH:i') : '') }}">
        @error('published_at')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</fieldset>

{{-- ================= SUBMIT ================= --}}
<div class="text-right">
    <button class="btn btn-success">
        <i class="fas fa-save"></i> {{ $buttonText ?? 'Save Post' }}
    </button>
</div>