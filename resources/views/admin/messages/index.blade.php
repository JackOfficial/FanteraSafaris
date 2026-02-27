<table class="table table-hover align-middle">
    <thead>
        <tr>
            <th>Status</th>
            <th>Traveler</th>
            <th>Subject</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($messages as $message)
        <tr>
            <td>
                @if($message->replied_at)
                    <span class="badge bg-success">
                        <i class="fas fa-check-double me-1"></i> Replied
                    </span>
                @elseif(!$message->is_read)
                    <span class="badge bg-danger animate__animated animate__flash animate__infinite">
                        <i class="fas fa-envelope me-1"></i> New
                    </span>
                @else
                    <span class="badge bg-warning text-dark">
                        <i class="fas fa-envelope-open me-1"></i> Read
                    </span>
                @endif
            </td>
            <td>
                <div class="fw-bold">{{ $message->name }}</div>
                <small class="text-muted">{{ $message->email }}</small>
            </td>
            <td>{{ Str::limit($message->subject, 40) }}</td>
            <td>{{ $message->created_at->format('M d, H:i') }}</td>
            <td>
                <a href="{{ route('admin.messages.show', $message) }}" class="btn btn-sm btn-outline-primary">
                    View
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>