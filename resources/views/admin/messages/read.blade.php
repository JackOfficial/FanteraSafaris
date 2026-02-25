@extends('admin.layouts.app')

@section('title', 'View Message')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>View Message</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.messages.inbox') }}">Messages</a></li>
                    <li class="breadcrumb-item active">View Message</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="container-fluid">

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title text-uppercase">Message from {{ $message->name }}</h3>
            <a href="{{ route('admin.messages.inbox') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Inbox
            </a>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <strong>Name:</strong> {{ $message->name }}
            </div>
            <div class="mb-3">
                <strong>Email:</strong> <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
            </div>
            <div class="mb-3">
                <strong>Subject:</strong> {{ $message->subject ?? '—' }}
            </div>
            <div class="mb-3">
                <strong>Message:</strong>
                <p class="border rounded p-3 bg-light text-dark">{{ $message->message }}</p>
            </div>
            <div class="mb-3">
                <strong>Sent At:</strong> {{ $message->created_at?->format('Y-m-d H:i') }}
            </div>
        </div>

        <div class="card-footer d-flex gap-2">
            {{-- Delete button --}}
            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" onclick="return confirm('Delete this message?')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>

            {{-- Back to Inbox --}}
            <a href="{{ route('admin.messages.inbox') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Inbox
            </a>
        </div>
    </div>

</div>
</section>

@endsection