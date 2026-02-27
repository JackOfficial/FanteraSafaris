@extends('admin.layouts.app')

@section('title', 'Edit Safari: ' . $package->name)

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <style>
        trix-toolbar .trix-button-group--file-tools { display: none !important; }
        .featured-preview { width: 100%; height: 220px; object-fit: cover; border-radius: 8px; }
        .trix-content { min-height: 250px !important; }
        .border-pink { border-left: 4px solid #e83e8c !important; }
        .text-pink { color: #e83e8c !important; }
        .btn-pink { background-color: #e83e8c; color: white; border: none; }
    </style>
@endpush

@section('content')

<section class="content">
    <div class="container-fluid">
        {{-- CALLING THE SINGLE VOLT COMPONENT HERE --}}
        <livewire:admin.edit-package :package="$package" />
    </div>
</section>
@endsection

@push('scripts')
    <script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
@endpush