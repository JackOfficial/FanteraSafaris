@extends('admin.layouts.app')
@section('title', "Edit: " . $destination->name)

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-3 align-items-center">
            <div class="col-sm-6">
                <h1 class="font-weight-bold">
                    <i class="fas fa-edit text-warning mr-2"></i>Edit Destination
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.destinations.index') }}" class="btn btn-light border shadow-sm btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    {{-- Initialize Alpine component with the existing image --}}
    <div class="container-fluid" x-data="imagePreview('{{ $destination->image ? asset('storage/' . $destination->image) : '' }}')">
        <form action="{{ route('admin.destinations.update', $destination->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0" style="border-radius: 12px;">
                        <div class="card-body p-4">
                            @include('admin.partials.alerts')

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold text-dark">Destination Name</label>
                                    <input type="text" name="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $destination->name) }}" required>
                                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold text-dark">Country</label>
                                    <select name="country" class="form-control @error('country') is-invalid @enderror" required>
                                        @foreach(['Uganda', 'Rwanda', 'Kenya', 'Tanzania'] as $country)
                                            <option value="{{ $country }}" {{ old('country', $destination->country) == $country ? 'selected' : '' }}>
                                                {{ $country }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label class="font-weight-bold text-dark">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="6">{{ old('description', $destination->description) }}</textarea>
                                @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="mt-4 p-3 border rounded bg-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <label class="font-weight-bold mb-0 text-dark d-block">Featured Destination</label>
                                        <small class="text-muted">Should this be showcased on the landing page?</small>
                                    </div>
                                    <div class="custom-control custom-switch custom-switch-md">
                                        <input type="checkbox" name="is_featured" value="1" 
                                               class="custom-control-input" id="featured" 
                                               {{ old('is_featured', $destination->is_featured) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="featured"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                        <div class="card-header bg-white font-weight-bold border-0">Image Settings</div>
                        <div class="card-body text-center pt-0">
                            {{-- Image Display Area --}}
                            <div class="mb-3 border rounded shadow-inner d-flex align-items-center justify-content-center bg-light" 
                                 style="height: 220px; overflow: hidden; border-style: dashed !important; border-width: 2px !important;">
                                <template x-if="imageUrl">
                                    <img :src="imageUrl" class="img-fluid w-100 h-100" style="object-fit: cover;">
                                </template>
                                <template x-if="!imageUrl">
                                    <div class="text-muted">
                                        <i class="fas fa-image fa-3x mb-2"></i>
                                        <p class="small">No image uploaded</p>
                                    </div>
                                </template>
                            </div>

                            {{-- File Selection --}}
                            <div class="custom-file">
                                <input type="file" name="image" class="custom-file-input" id="destImage" @change="fileChosen">
                                <label class="custom-file-label text-left" for="destImage" x-text="fileName">Change file...</label>
                            </div>

                            {{-- Alpine Dynamic Status Badge --}}
                            <div class="mt-3 text-left">
                                <span x-show="imageUrl !== '{{ asset('storage/' . $destination->image) }}'" 
                                      x-transition class="badge badge-warning py-2 px-3 w-100">
                                    <i class="fas fa-sync-alt mr-1"></i> New Image Selected
                                </span>
                                <span x-show="imageUrl === '{{ asset('storage/' . $destination->image) }}'" 
                                      class="badge badge-light border py-2 px-3 w-100 text-muted">
                                    <i class="fas fa-check-circle mr-1"></i> Using Saved Image
                                </span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning btn-block btn-lg shadow-sm font-weight-bold py-3 mb-2">
                        <i class="fas fa-save mr-2"></i> Update Destination
                    </button>
                    <p class="text-center small text-muted">Last updated: {{ $destination->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        </form>
    </div>
</section>

{{-- Alpine Preview Logic --}}
<script>
    function imagePreview(initialUrl) {
        return {
            imageUrl: initialUrl,
            fileName: 'Change file...',
            fileChosen(event) {
                const file = event.target.files[0];
                if (!file) return;
                
                this.fileName = file.name;
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = e => this.imageUrl = e.target.result;
            }
        }
    }
</script>

<style>
    .form-control:focus { border-color: #ffc107; box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.15); }
    .custom-file-label::after { background-color: #ffc107; color: #000; font-weight: bold; content: "Browse"; }
    .shadow-inner { box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06); }
</style>
@endsection