@extends('admin.layouts.app')

@section('title', 'Site Messages')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>All Messages ({{ $messages->total() }})</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Messages</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="container-fluid">

    {{-- Flash message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title text-uppercase">Messages Inbox</h3>
        </div>

        <div class="card-body table-responsive p-3">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-light">
                    <tr class="text-uppercase">
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Date Sent</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($messages as $message)
                    <tr>
                        <td>{{ $message->name }}</td>
                        <td>{{ $message->email }}</td>
                        <td>{{ $message->subject ?? '—' }}</td>
                        <td>{{ Str::limit($message->message, 50) }}</td>
                        <td>{{ $message->created_at?->format('Y-m-d H:i') }}</td>
                        <td class="d-flex gap-1">
                            {{-- View full message --}}
                            <a href="{{ route('admin.messages.read', $message->id) }}" 
                               class="btn btn-info btn-sm" title="View">
                                <i class="fas fa-eye"></i>
                            </a>

                            {{-- Delete message --}}
                            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" title="Delete" 
                                        onclick="return confirm('Delete this message?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">
                            <i class="fas fa-envelope-open-text"></i> No messages found.
                        </td>
                    </tr>
                @endforelse
                </tbody>

                <tfoot>
                    <tr class="text-uppercase">
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Date Sent</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="card-footer">
            {{ $messages->links() }}
        </div>
    </div>

</div>
</section>

@endsection