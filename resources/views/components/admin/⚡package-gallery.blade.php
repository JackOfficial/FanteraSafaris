<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SafariPackage;

new class extends Component
{
   use WithFileUploads;

    public SafariPackage $package;
    public $photos = [];

    public function save()
    {
        $this->validate(['photos.*' => 'image|max:2048']); // 2MB Max

        foreach ($this->photos as $photo) {
            $path = $photo->store('safaris', 'public');
            $this->package->images()->create(['path' => $path]);
        }

        $this->photos = []; // Clear input
        $this->package->load('images'); // Refresh list
    }

    public function deleteImage($id)
    {
        $image = $this->package->images()->find($id);
        \Storage::disk('public')->delete($image->path);
        $image->delete();
    }
};
?>

<div>
    {{-- Knowing is not enough; we must apply. Being willing is not enough; we must do. - Leonardo da Vinci --}}
</div>