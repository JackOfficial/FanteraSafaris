@extends('admin.layouts.app')

@section('title', 'Create Safari Category')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="font-weight-bold">Add New Category</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.safari-categories.index') }}">Categories</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.safari-categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                {{-- Left Side: Category Details --}}
                <div class="col-md-8">
                    <div class="card card-outline card-pink shadow-sm">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold">Category Details</h3>
                        </div>
                        
                        <div class="card-body">
                            <div class="form-group mb-4">
                                <label for="name" class="font-weight-bold">Category Name</label>
                                <input type="text" name="name" id="name" 
                                       class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                       placeholder="e.g. Luxury Safaris, Budget Tours" 
                                       value="{{ old('name') }}" required>
                                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group mb-0">
                                <label for="description" class="font-weight-bold">Description (Optional)</label>
                                <textarea name="description" id="description" rows="6" 
                                          class="form-control @error('description') is-invalid @enderror" 
                                          placeholder="Describe what kind of packages belong here...">{{ old('description') }}</textarea>
                                @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="card-footer bg-light d-flex justify-content-between">
                            <a href="{{ route('admin.safari-categories.index') }}" class="btn btn-link text-muted">
                                <i class="fas fa-arrow-left mr-1"></i> Back to List
                            </a>
                            <button type="submit" class="btn btn-pink px-5 font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Save Category
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Right Side: Media/Settings --}}
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold">Category Image</h3>
                        </div>
                        <div class="card-body text-center">
                            <p class="text-muted small mb-3">This image will appear as the header for this category's collection.</p>
                            
                            <div x-data="{ photoName: null, photoPreview: null }">
                                <input type="file" name="image" class="d-none" x-ref="photo"
                                    @change="
                                        photoName = $refs.photo.files[0].name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            photoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL($refs.photo.files[0]);
                                    ">
                                
                                {{-- Placeholder --}}
                                <div class="mt-2" x-show="! photoPreview">
                                    <div class="bg-light rounded border d-flex align-items-center justify-content-center mx-auto shadow-sm" 
                                         style="width: 100%; height: 200px; max-width: 280px;">
                                        <i class="fas fa-image fa-3x text-muted opacity-50"></i>
                                    </div>
                                </div>

                                {{-- New Image Preview --}}
                                <div class="mt-2" x-show="photoPreview" style="display: none;">
                                    <img :src="photoPreview" class="img-fluid rounded border shadow-sm mx-auto" 
                                         style="height: 200px; width: 100%; object-fit: cover; max-width: 280px;">
                                </div>

                                <button type="button" class="btn btn-outline-primary btn-sm mt-3 px-3 rounded-pill" @click.prevent="$refs.photo.click()">
                                    <i class="fas fa-cloud-upload-alt mr-1"></i> Choose Image
                                </button>
                                
                                @error('image') 
                                    <span class="text-danger d-block mt-2 small font-weight-bold">{{ $message }}</span> 
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info border-0 shadow-sm small">
                        <i class="fas fa-info-circle mr-1"></i> 
                        <strong>Pro-tip:</strong> Use high-resolution landscape images (at least 1200x600px) for the best look on your website's category pages.
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection