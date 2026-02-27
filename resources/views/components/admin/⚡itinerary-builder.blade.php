<?php

use Livewire\Component;

new class extends Component
{
    public $days = [];

    protected $rules = [
        'days.*.title' => 'required|min:3',
        'days.*.activities' => 'required|min:10',
        'days.*.meals' => 'nullable|string',
        'days.*.accommodation' => 'nullable|string',
    ];

    public function mount($existingDays = [])
    {
        if (!empty($existingDays)) {
            // Ensure days are sorted by day_number
            $this->days = collect($existingDays)->sortBy('day_number')->values()->toArray();
        } else {
            $this->addDay();
        }
    }

    public function addDay()
    {
        $this->days[] = [
            'day_number' => count($this->days) + 1,
            'title' => '',
            'activities' => '',
            'accommodation' => '',
            'meals' => 'Breakfast, Lunch, Dinner' // Pre-fill common East African standard
        ];
    }

    public function removeDay($index)
    {
        unset($this->days[$index]);
        $this->days = array_values($this->days); // Reset array keys
        
        // Re-index day numbers
        foreach ($this->days as $key => $day) {
            $this->days[$key]['day_number'] = $key + 1;
        }
    }
};
?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
        <div>
            <h5 class="mb-0 font-weight-bold"><i class="fas fa-map-signs mr-2 text-pink"></i>Safari Itinerary Builder</h5>
            <small class="text-white-50">Define the daily journey for your guests</small>
        </div>
        
        <button type="button" wire:click="addDay" class="btn btn-primary btn-sm shadow-sm rounded-pill px-3">
            <i class="fas fa-plus mr-1"></i> Add Next Day
        </button>
    </div>
    
    <div class="card-body bg-light">
        @foreach($days as $index => $day)
            {{-- CRITICAL: Use a very specific wire:key that includes the index --}}
            <div class="card mb-4 border-left border-primary border-width-3 shadow-sm" 
                 wire:key="day-container-{{ $index }}">
                
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <span class="badge badge-primary px-3 py-2" style="font-size: 0.9rem;">
                            DAY {{ $day['day_number'] }}
                        </span>
                        
                        @if(count($days) > 1)
                            <button type="button" 
                                    wire:click="removeDay({{ $index }})" 
                                    wire:confirm="Remove Day {{ $day['day_number'] }}?"
                                    class="btn btn-outline-danger btn-xs border-0">
                                <i class="fas fa-times-circle"></i> Remove Day
                            </button>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label class="form-label font-weight-bold small text-uppercase">Daily Heading</label>
                            <input type="text" 
                                   name="itinerary[{{ $index }}][title]" 
                                   wire:model.blur="days.{{ $index }}.title" 
                                   placeholder="e.g. Arrival and Sunset Boat Cruise"
                                   class="form-control @error("days.$index.title") is-invalid @enderror">
                        </div>
                        
                        <div class="col-md-5 mb-3">
                            <label class="form-label font-weight-bold small text-uppercase">Meals Included</label>
                            <input type="text" 
                                   name="itinerary[{{ $index }}][meals]" 
                                   wire:model.blur="days.{{ $index }}.meals" 
                                   class="form-control">
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label font-weight-bold small text-uppercase">Activities & Description</label>
                            {{-- Trix Integration with Alpine --}}
                            <div wire:ignore 
                                 x-data="{ 
                                    value: @entangle('days.'.$index.'.activities'),
                                    isSetByEditor: false
                                 }" 
                                 x-init="
                                    $refs.trix.editor.loadHTML(value);
                                    $watch('value', (v) => {
                                        if (!isSetByEditor) {
                                            $refs.trix.editor.loadHTML(v);
                                        }
                                        isSetByEditor = false;
                                    });
                                 "
                                 @trix-change="
                                    isSetByEditor = true;
                                    value = $event.target.value;
                                 "
                                 class="trix-container">
                                <input id="trix-{{ $index }}" type="hidden">
                                <trix-editor input="trix-{{ $index }}" x-ref="trix" class="bg-white border rounded min-height-150"></trix-editor>
                            </div>
                            @error("days.$index.activities") <small class="text-danger font-weight-bold">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label font-weight-bold small text-uppercase text-muted">Accommodation</label>
                            <input type="text" 
                                   name="itinerary[{{ $index }}][accommodation]" 
                                   wire:model.blur="days.{{ $index }}.accommodation" 
                                   placeholder="e.g. Mweya Safari Lodge"
                                   class="form-control form-control-sm">
                        </div>
                    </div>
                    
                    {{-- Hidden inputs for traditional form submission --}}
                    <input type="hidden" name="itinerary[{{ $index }}][day_number]" value="{{ $day['day_number'] }}">
                    <input type="hidden" name="itinerary[{{ $index }}][activities]" value="{{ $day['activities'] }}">
                </div>
            </div>
        @endforeach
    </div>

    <div class="card-footer bg-white py-3 text-center">
        <button type="button" wire:click="addDay" class="btn btn-outline-primary rounded-pill px-4">
            <i class="fas fa-plus-circle mr-1"></i> Add Day {{ count($days) + 1 }}
        </button>
    </div>

    <style>
        .min-height-150 { min-height: 150px !important; }
        .border-width-3 { border-left-width: 5px !important; }
        trix-toolbar .trix-button-group--file-tools { display: none !important; }
    </style>
</div>