@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">My Assigned Safaris</h2>
    
    <div class="row">
        @forelse($itineraries as $trip)
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-left-success">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ $trip->package_name }}</h5>
                        <p class="card-text">
                            <strong>Client:</strong> {{ $trip->customer_name }}<br>
                            <strong>Date:</strong> {{ $trip->start_date }} to {{ $trip->end_date }}<br>
                            <strong>Vehicle:</strong> {{ $trip->vehicle_plate ?? 'Not Assigned' }}
                        </p>
                        <a href="#" class="btn btn-sm btn-outline-primary">View Trip Details</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No safaris assigned to you at the moment.</div>
            </div>
        @endforelse
    </div>
</div>
@endsection