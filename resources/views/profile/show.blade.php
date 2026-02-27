@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" class="rounded-circle img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto shadow" 
                                 style="width: 120px; height: 120px; font-size: 2.5rem; font-weight: bold;">
                                {{ auth()->user()->initials() }}
                            </div>
                        @endif
                    </div>

                    <h2 class="fw-bold">{{ auth()->user()->name }}</h2>
                    <p class="text-muted mb-4">{{ auth()->user()->email }}</p>
                    
                    <span class="badge rounded-pill bg-success px-3 py-2">
                        {{ auth()->user()->getRoleNames()->first() ?? 'Client' }}
                    </span>

                    <hr class="my-4">

                    <div class="d-grid gap-2 d-md-block">
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary px-4">Edit Profile</a>
                        @role('tour-guide')
                            <a href="{{ route('guide.itinerary') }}" class="btn btn-success px-4">View My Safaris</a>
                        @endrole
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection