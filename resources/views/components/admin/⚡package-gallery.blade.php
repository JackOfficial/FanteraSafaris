<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SafariPackage;
use Illuminate\Support\Facades\Storage;

new class extends Component
{
    use WithFileUploads;

    public SafariPackage $package;
    public $uploads = []; // Changed name to avoid confusion with existing photos

    public function save()
    {
        $this->validate([
            'uploads.*' => 'image|max:3072', // Increased to 3MB for high-quality safari shots
        ]);

        foreach ($this->uploads as $file) {
            $path = $file->store('safaris/gallery', 'public');
            
            // Using the polymorphic 'photos' relationship with the 'imageable' morph
            $this->package->photos()->create([
                'path' => $path,
                'type' => 'gallery'
            ]);
        }

        $this->reset('uploads');
        $this->package->load('photos'); // Refresh relationship
        $this->dispatch('notify', 'Gallery updated successfully!');
    }

    public function deleteImage($id)
    {
        $photo = $this->package->photos()->findOrFail($id);
        
        // Physical file deletion
        if (Storage::disk('public')->exists($photo->path)) {
            Storage::disk('public')->delete($photo->path);
        }
        
        $photo->delete();
        $this->package->load('photos');
    }
};
?>

<div class="package-gallery-wrapper">
    {{-- Upload Section --}}
    <div class="upload-zone mb-4 p-3 border rounded bg-light" 
         style="border-style: dashed !important; border-width: 2px !important;">
        <div class="form-group mb-0">
            <label class="btn btn-primary btn-sm mb-0 cursor-pointer">
                <i class="fas fa-plus mr-1"></i> Select Images
                <input type="file" wire:model="uploads" multiple class="d-none">
            </label>
            <span class="ml-2 text-muted small" wire:loading.remove wire:target="uploads">
                PNG, JPG, WEBP up to 3MB
            </span>
            
            <div wire:loading wire:target="uploads" class="ml-2">
                <span class="spinner-border spinner-border-sm text-pink" role="status"></span>
                <small class="text-pink font-weight-bold ml-1">Uploading...</small>
            </div>
        </div>

        @if($uploads)
            <div class="mt-3 p-2 bg-white rounded border shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small font-weight-bold">{{ count($uploads) }} images ready to save</span>
                    <button wire:click="save" class="btn btn-success btn-sm">
                        <i class="fas fa-cloud-upload-alt mr-1"></i> Start Upload
                    </button>
                </div>
            </div>
        @endif
    </div>

    {{-- Gallery Grid --}}
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-2">
        @forelse($package->photos->where('type', 'gallery') as $photo)
            <div class="col mb-3">
                <div class="card h-100 shadow-sm position-relative overflow-hidden border-0 group">
                    <img src="{{ asset('storage/' . $photo->path) }}" 
                         class="card-img-top" 
                         style="height: 120px; object-fit: cover;">
                    
                    <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center bg-dark-50 opacity-0 hover-opacity-100 transition" 
                         style="top:0; left:0; background: rgba(0,0,0,0.4); transition: 0.3s;">
                        <button wire:click="deleteImage({{ $photo->id }})" 
                                wire:confirm="Are you sure you want to delete this image?"
                                class="btn btn-danger btn-xs shadow">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-4">
                <i class="fas fa-images fa-3x text-muted opacity-20 mb-2"></i>
                <p class="text-muted small">No images in gallery yet.</p>
            </div>
        @endforelse
    </div>

    <style>
        .cursor-pointer { cursor: pointer; }
        .hover-opacity-100:hover { opacity: 1 !important; }
        .btn-xs { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
    </style>
</div>