@extends('admin.layouts.app')

@section('title', 'Create Safari Package')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create New Safari</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.packages.index') }}">Packages</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-outline card-pink shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">Safari Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="name">Package Name</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. 5-Day Serengeti Great Migration" required>
                                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="description">Detailed Description</label>
                                <textarea name="description" id="description" rows="6" class="form-control @error('description') is-invalid @enderror" placeholder="Describe the highlights of the safari...">{{ old('description') }}</textarea>
                                @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="price">Starting Price (USD)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
                                        </div>
                                        @error('price') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="duration_days">Duration (Days)</label>
                                        <input type="number" name="duration_days" id="duration_days" class="form-control @error('duration_days') is-invalid @enderror" value="{{ old('duration_days') }}" required>
                                        @error('duration_days') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h3 class="card-title font-weight-bold">Featured Image</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-0 text-center">
                                <div x-data="{ photoName: null, photoPreview: null }" class="col-span-6 sm:col-span-4">
                                    <input type="file" name="image" class="d-none" x-ref="photo"
                                        @change="
                                            photoName = $refs.photo.files[0].name;
                                            const reader = new FileReader();
                                            reader.onload = (e) => {
                                                photoPreview = e.target.result;
                                            };
                                            reader.readAsDataURL($refs.photo.files[0]);
                                        ">
                                    
                                    <div class="mt-2" x-show="! photoPreview">
                                        <img src="{{ asset('images/placeholder-safari.jpg') }}" class="img-fluid rounded border shadow-sm" style="max-height: 200px;">
                                    </div>

                                    <div class="mt-2" x-show="photoPreview" style="display: none;">
                                        <img :src="photoPreview" class="img-fluid rounded border shadow-sm" style="max-height: 200px;">
                                    </div>

                                    <button type="button" class="btn btn-outline-primary btn-sm mt-3" @click.prevent="$refs.photo.click()">
                                        <i class="fas fa-image mr-1"></i> Select Safari Photo
                                    </button>
                                </div>
                                @error('image') <span class="text-danger d-block mt-2 small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="category_id">Category</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-0">
                                <label>Visibility</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="is_active" class="custom-control-input" id="isActive" checked>
                                    <label class="custom-control-label" for="isActive">Published</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light border-top-0">
                            <button type="submit" class="btn btn-pink btn-block font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Save Safari
                            </button>
                            <a href="{{ route('admin.packages.index') }}" class="btn btn-link btn-block btn-sm text-muted">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection