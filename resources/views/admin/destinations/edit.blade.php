@extends('admin.layouts.app')
@section('title', $destination->name)
@section('content')
   <div class="container-fluid" x-data="imagePreview('{{ $destination->image ? asset('storage/' . $destination->image) : '' }}')">
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title font-weight-bold mb-0">
                                <i class="fas fa-edit text-warning mr-2"></i> Edit Destination
                            </h3>
                            <a href="{{ route('admin.destinations.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Back to List
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        @include('admin.partials.alerts')

                        <form action="{{ route('admin.destinations.update', $destination->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">Destination Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $destination->name) }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">Country</label>
                                    <select name="country" class="form-control" required>
                                        @foreach(['Uganda', 'Rwanda', 'Kenya', 'Tanzania'] as $country)
                                            <option value="{{ $country }}" {{ old('country', $destination->country) == $country ? 'selected' : '' }}>
                                                {{ $country }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label class="font-weight-bold">Description</label>
                                <textarea name="description" class="form-control" rows="5">{{ old('description', $destination->description) }}</textarea>
                            </div>

                            <div class="row mt-4 align-items-center">
                                <div class="col-md-8">
                                    <label class="font-weight-bold">Change Cover Image</label>
                                    <div class="custom-file">
                                        {{-- Alpine @change triggers the preview logic --}}
                                        <input type="file" name="image" class="custom-file-input" id="destImage" @change="fileChosen">
                                        <label class="custom-file-label" for="destImage" x-text="fileName">Choose new file...</label>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="custom-control custom-switch mt-3">
                                        <input type="checkbox" name="is_featured" value="1" class="custom-control-input" id="featured" 
                                               {{ old('is_featured', $destination->is_featured) ? 'checked' : '' }}>
                                        <label class="custom-control-label font-weight-bold" for="featured">Featured Destination</label>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">
                            
                            <button type="submit" class="btn btn-warning px-5 font-weight-bold shadow-sm">
                                <i class="fas fa-save mr-2"></i> Update Destination
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Sidebar with Alpine Dynamic Preview --}}
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h5 class="card-title font-weight-bold mb-0">Visual Preview</h5>
                    </div>
                    <div class="card-body p-0 text-center">
                        {{-- Show new preview if exists, else show current, else show placeholder --}}
                        <template x-if="imageUrl">
                            <img :src="imageUrl" class="img-fluid w-100 shadow-sm" style="max-height: 300px; object-fit: cover;">
                        </template>
                        <template x-if="!imageUrl">
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        </template>
                        
                        <div class="p-3 text-left">
                            <p class="small text-muted mb-0"><strong>Status:</strong> 
                                <span x-show="imageUrl !== '{{ asset('storage/' . $destination->image) }}'" class="badge badge-warning">New Image Selected</span>
                                <span x-show="imageUrl === '{{ asset('storage/' . $destination->image) }}'" class="badge badge-light border">Current Image</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alpine Script --}}
    <script>
        function imagePreview(initialUrl) {
            return {
                imageUrl: initialUrl,
                fileName: 'Choose new file...',
                fileChosen(event) {
                    this.fileToDataUrl(event, src => this.imageUrl = src);
                    this.fileName = event.target.files[0].name;
                },
                fileToDataUrl(event, callback) {
                    if (! event.target.files.length) return;
                    let file = event.target.files[0];
                    let reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = e => callback(e.target.result);
                }
            }
        }
    </script>
@endsection