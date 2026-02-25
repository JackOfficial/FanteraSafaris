 {{-- Basic Info --}}
    <fieldset class="border rounded p-3 mb-4">
        <legend class="w-auto px-2 text-primary"><i class="fas fa-folder-open"></i> Basic Information</legend>

        {{-- Name --}}
        <div class="form-group mb-3">
            <label><i class="fas fa-tag"></i> Name *</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required>
            @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        {{-- Description --}}
        <div class="form-group mb-0">
            <label><i class="fas fa-align-left"></i> Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description ?? '') }}</textarea>
        </div>
    </fieldset>

    {{-- SEO --}}
    <fieldset class="border rounded p-3">
        <legend class="w-auto px-2 text-success"><i class="fas fa-search"></i> SEO Settings</legend>

        <div class="form-group mb-3">
            <label><i class="fas fa-heading"></i> Meta Title</label>
            <input type="text" name="meta_title" class="form-control"
                   value="{{ old('meta_title', $category->meta_title ?? '') }}">
        </div>

        <div class="form-group mb-0">
            <label><i class="fas fa-align-justify"></i> Meta Description</label>
            <textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description', $category->meta_description ?? '') }}</textarea>
        </div>
    </fieldset>
