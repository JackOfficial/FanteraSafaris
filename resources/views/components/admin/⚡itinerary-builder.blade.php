<?php

use Livewire\Component;

new class extends Component
{
   public $days = [];

    protected $rules = [
        'days.*.title' => 'required|min:3',
        'days.*.activities' => 'required|min:10',
        'days.*.meals' => 'nullable|string',
    ];

    public function mount($existingDays = [])
    {
        if (!empty($existingDays)) {
            $this->days = $existingDays;
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
            'meals' => ''
        ];
    }

    public function removeDay($index)
    {
        unset($this->days[$index]);
        $this->days = array_values($this->days);
        foreach ($this->days as $key => $day) {
            $this->days[$key]['day_number'] = $key + 1;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
};
?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0"><i class="fas fa-route me-2"></i>Safari Itinerary Builder</h5>
            <small class="text-light-50">Use the rich text editor for detailed activities</small>
        </div>
        
        <button type="button" wire:click="addDay" wire:loading.attr="disabled" class="btn btn-primary shadow-sm">
            <span wire:loading.remove wire:target="addDay"><i class="fas fa-plus-circle me-1"></i> Add Next Day</span>
            <span wire:loading wire:target="addDay"><i class="fas fa-spinner fa-spin me-1"></i> Adding...</span>
        </button>
    </div>
    
    <div class="card-body bg-light">
        <div wire:loading wire:target="removeDay" class="text-center py-3 text-danger">
            <i class="fas fa-sync fa-spin me-2"></i> Updating itinerary order...
        </div>

        @foreach($days as $index => $day)
            <div class="card mb-4 border-start border-primary border-4 shadow-sm" 
                 wire:key="itinerary-day-{{ $index }}-{{ count($days) }}"
                 wire:loading.class="opacity-50" wire:target="removeDay({{ $index }})">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-primary fs-6 px-3 py-2">Day {{ $day['day_number'] }}</span>
                        
                        @if(count($days) > 1)
                            <button type="button" wire:click="removeDay({{ $index }})" wire:loading.attr="disabled"
                                    wire:confirm="Are you sure you want to remove Day {{ $day['day_number'] }}?"
                                    class="btn btn-outline-danger btn-sm border-0">
                                <i class="fas fa-trash-alt" wire:loading.remove wire:target="removeDay({{ $index }})"></i>
                                <i class="fas fa-spinner fa-spin" wire:loading wire:target="removeDay({{ $index }})"></i>
                            </button>
                        @endif
                    </div>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Daily Title</label>
                            <input type="text" name="itinerary[{{ $index }}][title]" 
                                   wire:model.blur="days.{{ $index }}.title" 
                                   class="form-control @error("days.$index.title") is-invalid @enderror">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Meals</label>
                            <input type="text" name="itinerary[{{ $index }}][meals]" 
                                   wire:model.blur="days.{{ $index }}.meals" class="form-control">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Activities & Highlights</label>
                            <div wire:ignore 
                                 x-data="{ 
                                    content: @entangle('days.'.$index.'.activities'),
                                    isFocused: false 
                                 }" 
                                 x-init="$refs.trix.editor.loadHTML(content)"
                                 @trix-change="content = $event.target.value"
                                 class="trix-container">
                                <input id="trix-{{ $index }}" type="hidden" name="itinerary[{{ $index }}][activities]">
                                <trix-editor input="trix-{{ $index }}" x-ref="trix" class="bg-white rounded shadow-sm"></trix-editor>
                            </div>
                            @error("days.$index.activities") <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    
                    <input type="hidden" name="itinerary[{{ $index }}][day_number]" value="{{ $day['day_number'] }}">
                </div>
            </div>
        @endforeach
    </div>

    <div class="card-footer bg-white py-3 text-end">
        <button type="button" wire:click="addDay" wire:loading.attr="disabled" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-plus me-1" wire:loading.remove wire:target="addDay"></i>
            <i class="fas fa-circle-notch fa-spin me-1" wire:loading wire:target="addDay"></i>
            Add Day {{ count($days) + 1 }}
        </button>
    </div>
</div>