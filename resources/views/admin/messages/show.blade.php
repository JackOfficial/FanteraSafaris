@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">From: {{ $message->name }} ({{ $message->email }})</h3>
                </div>
                <div class="card-body">
                    <p><strong>Subject:</strong> {{ $message->subject }}</p>
                    <hr>
                    <p>{{ $message->message }}</p>
                </div>
            </div>

            <div class="card card-outline card-success mt-4">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-reply me-2"></i> Send Reply</h3>
                </div>
                <div class="card-body">
                    @if($message->replied_at)
                        <div class="alert alert-light border">
                            <strong>Your previous reply ({{ $message->replied_at->diffForHumans() }}):</strong><br>
                            {{ $message->reply_message }}
                        </div>
                    @endif

                    <form action="{{ route('admin.messages.reply', $message) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <textarea name="reply_message" class="form-control" rows="5" placeholder="Write your response here..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success mt-3">Send Reply via Email</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection