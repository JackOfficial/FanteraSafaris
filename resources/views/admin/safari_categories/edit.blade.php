@extends('admin.layouts.app')

@section('title', 'Edit Category: ' . $safariCategory->name)

@section('content')

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.safari-categories.update', $safariCategory) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                {{-- Left Column: Category Info --}}
                <div class="col-md-8">
                    <div class="card card-outline card-pink shadow-sm">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-edit mr-2 text-pink"></i> Category Information
                            </h3>
                        </div>
                        
                        <div class="card-body">
                            <div class="form-group mb-4">
                                <label for="name" class="font-weight-bold">Category Name</label>
                                <input type="text" name="name" id="name" 
                                       class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $safariCategory->name) }}" required>
                                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="text-muted small font-weight-bold text-uppercase">URL Slug</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-link small"></i></span>
                                    </div>
                                    <input type="text" class="form-control bg-light border-left-0" value="{{ $safariCategory->slug }}" readonly>
                                </div>
                                <small class="text-muted mt-1 d-block">The slug is generated automatically from the name for SEO purposes.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label for="description" class="font-weight-bold">Description</label>
                                <textarea name="description" id="description" rows="6" 
                                          class="form-control @error('description') is-invalid @enderror" 
                                          placeholder="Enter category details...">{{ old('description', $safariCategory->description) }}</textarea>
                                @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="card-footer bg-white d-flex justify-content-between py-3">
                            <a href="{{ route('admin.safari-categories.index') }}" class="btn btn-link text-muted">
                                <i class="fas fa-chevron-left mr-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-pink px-5 font-weight-bold shadow-sm">
                                <i class="fas fa-save mr-1"></i> Update Category
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Media & Meta --}}
                <div class="col-md-4">
                    {{-- Polymorphic Photo Card --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold text-dark">Category Image</h3>
                        </div>
                        <div class="card-body text-center">
                            <div x-data="{ photoPreview: null }">
                                <input type="file" name="image" class="d-none" x-ref="photo"
                                    @change="
                                        const reader = new FileReader();
                                        reader.onload = (e) => { photoPreview = e.target.result; };
                                        reader.readAsDataURL($refs.photo.files[0]);
                                    ">
                                
                                {{-- Current Image Logic --}}
                                <div class="mt-2" x-show="! photoPreview">
                                    @php $photo = $safariCategory->photo; @endphp
                                    @if($photo)
                                        <img src="{{ asset('storage/' . $photo->path) }}" 
                                             class="img-fluid rounded border shadow-sm" 
                                             style="height: 200px; width: 100%; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded border d-flex align-items-center justify-content-center mx-auto shadow-sm" 
                                             style="height: 200px; width: 100%;">
                                            <i class="fas fa-image fa-3x text-muted opacity-50"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- New Image Preview --}}
                                <div class="mt-2" x-show="photoPreview" style="display: none;">
                                    <img :src="photoPreview" class="img-fluid rounded border shadow-sm" 
                                         style="height: 200px; width: 100%; object-fit: cover;">
                                </div>

                                <button type="button" class="btn btn-outline-primary btn-sm mt-3 px-4 rounded-pill" @click.prevent="$refs.photo.click()">
                                    <i class="fas fa-sync-alt mr-1"></i> Change Photo
                                </button>
                                
                                @error('image') <span class="text-danger d-block mt-2 small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Stats/Info Card --}}
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="font-weight-bold mb-3">Quick Stats</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Packages:</span>
                                <span class="badge badge-pill badge-light border px-3">{{ $safariCategory->safariPackages()->count() }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Created:</span>
                                <span class="small">{{ $safariCategory->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection