<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SafariPackage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    public SafariPackage $package;
    
    public $name, $price, $duration_days, $status, $description;
    public $selected_destinations = []; 
    public $selected_categories = [];
    
    public $featured_image; 
    public $gallery_images = []; 
    public $itinerary = [];

    public function mount(SafariPackage $package)
    {
        $this->package = $package->load(['itineraries', 'photos', 'categories', 'destinations']);
        
        $this->name = $package->name;
        $this->price = $package->price;
        $this->duration_days = $package->duration_days;
        $this->status = $package->status;
        $this->description = $package->description;

        $this->selected_destinations = $this->package->destinations->pluck('id')->map(fn($id) => (string)$id)->toArray();
        $this->selected_categories = $this->package->categories->pluck('id')->map(fn($id) => (string)$id)->toArray();
        
        // We add a 'temp_id' to every row so Livewire can track it reliably in the loop
        $this->itinerary = $this->package->itineraries->sortBy('day_number')->values()->map(fn($day) => [
            'temp_id' => Str::random(8),
            'day_number' => $day->day_number,
            'title' => $day->title,
            'activities' => $day->activities,
            'meals' => $day->meals,
            'accommodation' => $day->accommodation
        ])->toArray();
    }

    public function deletePhoto($photoId)
    {
        $photo = $this->package->photos()->find($photoId);
        if ($photo) {
            Storage::disk('public')->delete($photo->path);
            $photo->delete();
            $this->package->load('photos'); 
        }
    }

    public function addDay()
    {
        $this->itinerary[] = [
            'temp_id' => Str::random(8),
            'day_number' => count($this->itinerary) + 1,
            'title' => '', 
            'activities' => '', 
            'meals' => 'Breakfast, Lunch, Dinner', 
            'accommodation' => ''
        ];
    }

    public function duplicateDay($index)
    {
        $newDay = $this->itinerary[$index];
        $newDay['temp_id'] = Str::random(8); // Give the duplicate its own identity
        array_splice($this->itinerary, $index + 1, 0, [$newDay]);
        $this->reorderDays();
    }

    public function removeDay($index)
    {
        unset($this->itinerary[$index]);
        $this->reorderDays();
    }

    protected function reorderDays()
    {
        $this->itinerary = array_values($this->itinerary);
        foreach ($this->itinerary as $k => $v) { 
            $this->itinerary[$k]['day_number'] = $k + 1; 
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:safari_packages,name,' . $this->package->id,
            'price' => 'required|numeric',
            'selected_destinations' => 'required|array|min:1',
            'selected_categories' => 'required|array|min:1',
            'description' => 'required',
            'gallery_images.*' => 'image|max:2048',
        ]);

        DB::transaction(function () {
            $this->package->update([
                'name' => $this->name, 
                'slug' => Str::slug($this->name),
                'price' => $this->price, 
                'duration_days' => count($this->itinerary),
                'status' => $this->status,
                'description' => $this->description,
            ]);

            $this->package->destinations()->sync($this->selected_destinations);
            $this->package->categories()->sync($this->selected_categories);

            if ($this->featured_image) {
                $old = $this->package->photos()->where('type', 'featured')->first();
                if ($old) { Storage::disk('public')->delete($old->path); $old->delete(); }
                $path = $this->featured_image->store('safaris/featured', 'public');
                $this->package->photos()->create(['path' => $path, 'type' => 'featured']);
            }

            foreach ($this->gallery_images as $image) {
                $path = $image->store('safaris/gallery', 'public');
                $this->package->photos()->create(['path' => $path, 'type' => 'gallery']);
            }

            // Clean up and save Itineraries
            $this->package->itineraries()->delete();
            foreach ($this->itinerary as $day) { 
                // Remove the helper temp_id before database insertion
                $data = collect($day)->except('temp_id')->toArray();
                $this->package->itineraries()->create($data); 
            }
        });

        session()->flash('success', 'Safari updated successfully!');
        return redirect()->route('admin.packages.index');
    }
}; ?>

<div x-data="{ 
    expandedId: null,
    price: @entangle('price'),
    discountRate: 0,
    destinations: @entangle('selected_destinations'),
    categories: @entangle('selected_categories'),

    get solo() { return (this.price * (1 - (this.discountRate / 100))).toFixed(2) },
    get couple() { return (this.price * 2 * (1 - (this.discountRate / 100))).toFixed(2) },
    get group() { return (this.price * 4 * (1 - (this.discountRate / 100))).toFixed(2) },

    toggle(id, list) {
        id = id.toString();
        const index = this[list].indexOf(id);
        if (index > -1) { this[list].splice(index, 1); } 
        else { this[list].push(id); }
    }
}">

@push('styles')
<style>
/* ================= GLOBAL POLISH ================= */
.card { border-radius: 16px !important; }
.form-control:focus { box-shadow: 0 0 0 0.15rem rgba(232,62,140,.15); }
.transition-soft { transition: all .25s ease; }

/* ================= BADGES ================= */
.badge-choice {
    cursor: pointer;
    transition: all .2s ease;
    border: 1px solid #dee2e6;
    user-select: none;
    font-weight: 500;
}
.badge-choice:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,.08);
}
.badge-choice.active-dest { background:#e83e8c; color:white; border-color:#e83e8c; }
.badge-choice.active-cat { background:#343a40; color:white; border-color:#343a40; }

/* ================= PRICE CARD ================= */
.price-preview {
    background: linear-gradient(135deg,#fff5f8,#ffffff);
    border:1px solid #f3d1e3;
}

/* ================= GALLERY ================= */
.gallery-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(110px,1fr));
    gap:12px;
}
.gallery-item { position:relative; height:110px; }
.gallery-item img {
    width:100%;
    height:100%;
    object-fit:cover;
    border-radius:10px;
    transition:.3s ease;
}
.gallery-item:hover img { transform:scale(1.05); }
.delete-overlay {
    position:absolute;
    top:-6px;
    right:-6px;
    background:#dc3545;
    color:white;
    border-radius:50%;
    width:24px;
    height:24px;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    border:2px solid white;
}

/* ================= ITINERARY ================= */
.itinerary-timeline {
    position:relative;
    padding-left:25px;
    border-left:2px dashed #f3d1e3;
    margin-left:15px;
}
.itinerary-day-node {
    position:absolute;
    left:-33px;
    width:20px;
    height:20px;
    background:#fff;
    border:4px solid #e83e8c;
    border-radius:50%;
    top:22px;
}
.card-itinerary {
    border:1px solid #eee;
    transition:.3s ease;
    margin-bottom:1.5rem;
}
.card-itinerary.active {
    border-color:#e83e8c;
    box-shadow:0 12px 25px rgba(232,62,140,.15);
    transform:translateY(-2px);
}
.itinerary-header {
    cursor:pointer;
    background:white !important;
}
.itinerary-header:hover { background:#fff5f8 !important; }

/* ================= FEATURED ================= */
.featured-preview {
    width:100%;
    height:240px;
    object-fit:cover;
    border-radius:14px;
    transition:.3s ease;
}
.featured-preview:hover {
    filter:brightness(.85);
}

/* ================= STICKY BAR ================= */
.sticky-action-bar {
    position:sticky;
    bottom:20px;
    z-index:100;
    background:linear-gradient(135deg,#ffffff,#fdf2f7);
    border:1px solid #f3d1e3;
    border-radius:60px;
    padding:14px 30px;
}
.btn-pink {
    background:#e83e8c;
    color:white;
    transition:.25s ease;
}
.btn-pink:hover {
    transform:translateY(-2px);
    box-shadow:0 6px 18px rgba(232,62,140,.35);
}
[x-cloak]{ display:none !important; }
</style>
@endpush


<section class="content">
<div class="container-fluid py-4">
<form wire:submit="save">

<div class="row">

<!-- ================= LEFT COLUMN ================= -->
<div class="col-lg-4 col-md-5">

<div class="card shadow-sm border-0 mb-4">
<div class="card-body">

<h5 class="font-weight-bold mb-4">Package Essentials</h5>

<label class="small font-weight-bold text-muted">PACKAGE NAME</label>
<input type="text" wire:model="name"
class="form-control form-control-lg bg-light border-0 rounded-pill px-4 mb-4">

<div class="row mb-4">
<div class="col-6">
<label class="small font-weight-bold text-muted">BASE PRICE</label>
<input type="number" x-model="price"
class="form-control bg-light border-0 rounded-pill px-4">
</div>
<div class="col-6">
<label class="small font-weight-bold text-muted">DISCOUNT %</label>
<input type="number" x-model="discountRate"
class="form-control bg-light border-0 rounded-pill px-4">
</div>
</div>

<div class="price-preview p-4 rounded mb-4">
<label class="small font-weight-bold text-pink d-block mb-3">LIVE QUOTE PREVIEW</label>
<div class="d-flex justify-content-between mb-1">
<span>Solo</span>
<span class="font-weight-bold">$<span x-text="solo"></span></span>
</div>
<div class="d-flex justify-content-between mb-1">
<span>Couple</span>
<span class="font-weight-bold">$<span x-text="couple"></span></span>
</div>
<div class="d-flex justify-content-between">
<span class="text-pink font-weight-bold">Group (4+)</span>
<span class="text-pink font-weight-bold">$<span x-text="group"></span></span>
</div>
</div>

<label class="small font-weight-bold text-muted d-flex justify-content-between">
DESTINATIONS
<span x-text="destinations.length + ' selected'"></span>
</label>

<div class="d-flex flex-wrap mb-4">
@foreach(\App\Models\Destination::orderBy('name')->get() as $dest)
<div @click="toggle({{ $dest->id }}, 'destinations')"
class="badge badge-choice px-3 py-2 m-1 rounded-pill"
:class="destinations.includes('{{ $dest->id }}') ? 'active-dest' : 'bg-white'">
{{ $dest->name }}
</div>
@endforeach
</div>

<label class="small font-weight-bold text-muted d-flex justify-content-between">
CATEGORIES
<span x-text="categories.length + ' selected'"></span>
</label>

<div class="d-flex flex-wrap">
@foreach(\App\Models\SafariCategory::orderBy('name')->get() as $cat)
<div @click="toggle({{ $cat->id }}, 'categories')"
class="badge badge-choice px-3 py-2 m-1 rounded-pill"
:class="categories.includes('{{ $cat->id }}') ? 'active-cat' : 'bg-white'">
{{ $cat->name }}
</div>
@endforeach
</div>

</div>
</div>

</div>


<!-- ================= RIGHT COLUMN ================= -->
<div class="col-lg-8 col-md-7">

{{-- KEEP YOUR EXISTING FEATURED IMAGE + ITINERARY + TRIX + SAVE BAR EXACTLY AS BEFORE --}}

</div>

</div>
</form>
</div>
</section>

</div>