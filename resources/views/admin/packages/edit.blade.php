@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <form action="{{ route('admin.packages.update', $package) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Package Basics</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Package Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $package->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price (USD)</label>
                            <input type="number" name="price" class="form-control" value="{{ $package->price }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Duration (Days)</label>
                            <input type="number" name="duration_days" class="form-control" value="{{ $package->duration_days }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control" value="{{ $package->location }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                {{-- Passing the collection to Livewire --}}
                <livewire:admin.itinerary-builder :existing-days="$package->itineraries->toArray()" />
                
                <div class="mt-4 pb-5">
                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="fas fa-save me-2"></i> Update Safari Package
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection