@extends('admin.layouts.app')
@section('title', "Add Destination")

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="font-weight-bold">Create Destination</h1>
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
    <div class="container-fluid" x-data="destinationForm()">
        <form action="{{ route('admin.destinations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
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
                                           placeholder="e.g. Serengeti National Park" 
                                           value="{{ old('name') }}" required>
                                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold text-dark">Country</label>
                                    <select name="country" class="form-control select2 @error('country') is-invalid @enderror" required>
                                        <option value="" selected disabled>Select Country</option>
                                        <option value="Uganda" {{ old('country') == 'Uganda' ? 'selected' : '' }}>Uganda</option>
                                        <option value="Rwanda" {{ old('country') == 'Rwanda' ? 'selected' : '' }}>Rwanda</option>
                                        <option value="Kenya" {{ old('country') == 'Kenya' ? 'selected' : '' }}>Kenya</option>
                                        <option value="Tanzania" {{ old('country') == 'Tanzania' ? 'selected' : '' }}>Tanzania</option>
                                    </select>
                                    @error('country') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label class="font-weight-bold text-dark">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="6" placeholder="Write a captivating summary for travelers...">{{ old('description') }}</textarea>
                                @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="mt-4 p-3 bg-light" style="border-radius: 8px;">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="is_featured" value="1" 
                                           class="custom-control-input" id="featured" 
                                           {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="featured">
                                        Mark as Featured Destination
                                    </label>
                                    <small class="d-block text-muted">Featured destinations appear on the homepage slider/section.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0" style="border-radius: 12px;">
                        <div class="card-header bg-white font-weight-bold">Cover Image</div>
                        <div class="card-body text-center">
                            {{-- Live Preview Area --}}
                            <div class="mb-3 border rounded d-flex align-items-center justify-content-center bg-light" 
                                 style="height: 200px; overflow: hidden; position: relative;">
                                <template x-if="imageUrl">
                                    <img :src="imageUrl" class="img-fluid w-100 h-100" style="object-fit: cover;">
                                </template>
                                <template x-if="!imageUrl">
                                    <div class="text-muted">
                                        <i class="fas fa-image fa-3x mb-2"></i>
                                        <p class="small">Image Preview</p>
                                    </div>
                                </template>
                            </div>

                            <div class="custom-file">
                                <input type="file" name="image" class="custom-file-input" id="imageInput" 
                                       @change="fileChosen" accept="image/*">
                                <label class="custom-file-label text-left" for="imageInput" x-text="fileName">Choose file...</label>
                            </div>
                            <small class="text-muted mt-2 d-block text-left">Recommended: 1200 x 800px (Max 2MB)</small>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-warning btn-block btn-lg shadow-sm font-weight-bold py-3">
                            <i class="fas fa-save mr-2"></i> Save Destination
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

{{-- Alpine.js Logic --}}
<script>
    function destinationForm() {
        return {
            imageUrl: null,
            fileName: 'Choose file...',
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
    .form-control:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.15);
    }
    .card { transition: transform 0.2s; }
    .custom-file-label::after { background-color: #ffc107; color: #000; font-weight: bold; content: "Browse"; }
</style>
@endsection