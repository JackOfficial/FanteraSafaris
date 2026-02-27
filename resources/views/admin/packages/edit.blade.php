@extends('admin.layouts.app')
@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <style>
        trix-toolbar .trix-button-group--file-tools { display: none !important; } /* Hide file upload in Trix since we have a dedicated Gallery */
    </style>
@endpush
@section('content')
<div class="container-fluid">
    {{-- Header with Quick Stats/Links --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Edit Safari: {{ $package->name }}</h1>
        <a href="{{ route('admin.bookings.index', ['package_id' => $package->id]) }}" class="btn btn-outline-primary">
            <i class="fas fa-calendar-check me-1"></i> View Bookings
        </a>
    </div>

    <form action="{{ route('admin.packages.update', $package) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            {{-- Left Column: Basics & Description --}}
            <div class="col-md-5">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Package Basics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Package Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $package->name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Price (USD)</label>
                                <input type="number" name="price" class="form-control" value="{{ $package->price }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Duration (Days)</label>
                                <input type="number" name="duration_days" class="form-control" value="{{ $package->duration_days }}" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Location</label>
                                <input type="text" name="location" class="form-control" value="{{ $package->location }}" required>
                            </div>
                        </div>

                        {{-- Trix Editor for Main Package Description --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">General Overview</label>
                            <div x-data="{ content: @js($package->description) }" x-init="$refs.trix.editor.loadHTML(content)">
                                <input id="description" type="hidden" name="description" x-model="content">
                                <trix-editor input="description" x-ref="trix" class="bg-white border-1" style="min-height: 250px;"></trix-editor>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Gallery Component --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-images me-2"></i>Package Gallery</h5>
                    </div>
                    <div class="card-body">
                        <livewire:admin.package-gallery :package="$package" />
                    </div>
                </div>
            </div>

            {{-- Right Column: Itinerary --}}
            <div class="col-md-7">
                <livewire:admin.itinerary-builder :existing-days="$package->itineraries->toArray()" />
                
                <div class="mt-4 pb-5">
                    <button type="submit" class="btn btn-success btn-lg w-100 shadow-sm">
                        <i class="fas fa-save me-2"></i> Save All Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
@endpush