@extends('admin.layouts.app')

@section('title', 'Edit Safari: ' . $package->name)

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <style>
        trix-toolbar .trix-button-group--file-tools { display: none !important; }
        .featured-preview { width: 100%; height: 200px; object-fit: cover; border-radius: 8px; }
    </style>
@endpush

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Safari</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.bookings.index', ['package_id' => $package->id]) }}" class="btn btn-outline-primary shadow-sm">
                    <i class="fas fa-calendar-check mr-1"></i> View Bookings
                </a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.packages.update', $package) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Left Column: Basics & Media --}}
                <div class="col-md-5">
                    {{-- Basic Info Card --}}
                    <div class="card card-outline card-pink shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 font-weight-bold">Package Basics</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Package Name</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $package->name) }}" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Price (USD)</label>
                                    <input type="number" name="price" class="form-control" value="{{ old('price', $package->price) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Duration (Days)</label>
                                    <input type="number" name="duration_days" class="form-control" value="{{ old('duration_days', $package->duration_days) }}" required>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Location</label>
                                <input type="text" name="location" class="form-control" value="{{ old('location', $package->location) }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="safari_category_id">Safari Category</label>
                                <select name="safari_category_id" id="safari_category_id" class="form-control @error('safari_category_id') is-invalid @enderror" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (old('safari_category_id', $package->safari_category_id) == $category->id) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="status">Publication Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="draft" {{ old('status', $package->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $package->status) == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Featured Image Card (Morphable) --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0 font-weight-bold">Featured Image</h5>
                        </div>
                        <div class="card-body text-center">
                            <div x-data="{ photoPreview: null }">
                                <input type="file" name="featured_image" class="d-none" x-ref="photo"
                                    @change="
                                        const reader = new FileReader();
                                        reader.onload = (e) => { photoPreview = e.target.result; };
                                        reader.readAsDataURL($refs.photo.files[0]);
                                    ">
                                
                                <div class="mt-2" x-show="! photoPreview">
                                    @php $featured = $package->photos->firstWhere('type', 'featured'); @endphp
                                    <img src="{{ $featured ? asset('storage/' . $featured->path) : asset('front/images/placeholder-safari.jpg') }}" 
                                         class="featured-preview border shadow-sm">
                                </div>

                                <div class="mt-2" x-show="photoPreview" style="display: none;">
                                    <img :src="photoPreview" class="featured-preview border shadow-sm">
                                </div>

                                <button type="button" class="btn btn-outline-primary btn-sm mt-3" @click.prevent="$refs.photo.click()">
                                    <i class="fas fa-sync-alt mr-1"></i> Change Featured Photo
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Gallery Component (Polymorphic Livewire) --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="fas fa-images mr-2"></i>Package Gallery</h5>
                        </div>
                        <div class="card-body">
                            {{-- This Livewire component should handle morphMany photos with type 'gallery' --}}
                            <livewire:admin.package-gallery :package="$package" />
                        </div>
                    </div>
                </div>

                {{-- Right Column: Description & Itinerary --}}
                <div class="col-md-7">
                    {{-- Trix Editor --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 font-weight-bold">General Overview</h5>
                        </div>
                        <div class="card-body">
                            <div x-data="{ content: @js($package->description) }" x-init="$refs.trix.editor.loadHTML(content)">
                                <input id="description" type="hidden" name="description" x-model="content">
                                <trix-editor input="description" x-ref="trix" class="bg-white border-1" style="min-height: 250px;"></trix-editor>
                            </div>
                        </div>
                    </div>

                    {{-- Itinerary Builder --}}
                    <livewire:admin.itinerary-builder :existing-days="$package->itineraries->toArray()" />
                    
                    <div class="mt-4 pb-5">
                        <button type="submit" class="btn btn-success btn-lg btn-block shadow-sm py-3">
                            <i class="fas fa-save mr-2"></i> Update Safari Package
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
    <script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
@endpush