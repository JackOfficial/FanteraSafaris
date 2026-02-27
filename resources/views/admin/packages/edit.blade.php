@extends('admin.layouts.app')

@section('title', 'Edit Safari: ' . $package->name)

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <style>
        trix-toolbar .trix-button-group--file-tools { display: none !important; }
        .featured-preview { width: 100%; height: 220px; object-fit: cover; border-radius: 8px; }
        .trix-content { min-height: 250px !important; }
    </style>
@endpush

@section('content')
<section class="content-header">
    <div class="container-fluid">

        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="font-weight-bold">Edit Safari Package</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.packages.index') }}" class="btn btn-default btn-sm mr-2">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </a>
                <a href="{{ route('admin.bookings.index', ['package_id' => $package->id]) }}" class="btn btn-outline-primary btn-sm shadow-sm">
                    <i class="fas fa-calendar-check mr-1"></i> View Bookings
                </a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <form action="{{ route('admin.packages.update', $package) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Left Column: Basics & Media --}}
                <div class="col-md-5">
                    {{-- Basic Info Card --}}
                    <div class="card card-outline card-pink shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 font-weight-bold text-pink">Package Basics</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold small">PACKAGE NAME</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $package->name) }}" required>
                                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small">PRICE (USD)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                        <input type="number" name="price" class="form-control" value="{{ old('price', $package->price) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small">DURATION (DAYS)</label>
                                    <input type="number" name="duration_days" class="form-control" value="{{ old('duration_days', $package->duration_days) }}" required>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold small">LOCATION / DESTINATIONS</label>
                                <input type="text" name="location" class="form-control" value="{{ old('location', $package->location) }}" placeholder="e.g. Bwindi & Queen Elizabeth" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold">CATEGORY</label>
                                    <select name="safari_category_id" class="form-control" required>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('safari_category_id', $package->safari_category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold">STATUS</label>
                                    <select name="status" class="form-control">
                                        <option value="draft" {{ old('status', $package->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ old('status', $package->status) == 'published' ? 'selected' : '' }}>Published</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Featured Image Card --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 font-weight-bold">Featured Cover Image</h5>
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
                                    <img src="{{ $featured ? asset('storage/' . $featured->path) : asset('front/images/placeholder.jpg') }}" 
                                         class="featured-preview border shadow-sm img-thumbnail">
                                </div>

                                <div class="mt-2" x-show="photoPreview" style="display: none;">
                                    <img :src="photoPreview" class="featured-preview border shadow-sm img-thumbnail">
                                </div>

                                <button type="button" class="btn btn-outline-pink btn-sm mt-3 px-3 rounded-pill" @click.prevent="$refs.photo.click()">
                                    <i class="fas fa-camera mr-1"></i> Change Cover Photo
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Gallery Component --}}
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="fas fa-images mr-2 text-pink"></i>Package Gallery</h5>
                        </div>
                        <div class="card-body bg-light">
                            <livewire:admin.package-gallery :package="$package" />
                        </div>
                    </div>
                </div>

                {{-- Right Column: Description & Itinerary --}}
                <div class="col-md-7">
                    {{-- Trix Editor --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white d-flex justify-content-between">
                            <h5 class="mb-0 font-weight-bold">General Overview</h5>
                            <span class="badge badge-light border text-muted px-3">Rich Text Editor</span>
                        </div>
                        <div class="card-body">
                            <div x-data="{ content: @js($package->description) }" 
                                 x-init="$refs.trix.editor.loadHTML(content)">
                                {{-- NO x-model here to avoid conflict with Trix --}}
                                <input id="description" type="hidden" name="description" value="{{ old('description', $package->description) }}">
                                <trix-editor input="description" x-ref="trix" class="bg-white border rounded"></trix-editor>
                            </div>
                        </div>
                    </div>

                    {{-- Itinerary Builder --}}
                    <livewire:admin.itinerary-builder :existing-days="$package->itineraries->toArray()" />
                    
                    <div class="mt-4 pb-5">
                        <button type="submit" class="btn btn-pink btn-lg btn-block shadow py-3 font-weight-bold">
                            <i class="fas fa-save mr-2"></i> SAVE ALL CHANGES
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