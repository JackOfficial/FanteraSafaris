<table class="table">
    <thead>
        <tr>
            <th>Package Name</th>
            <th>Price</th>
            <th>Days</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($packages as $package)
            <tr>
                <td><strong>{{ $package->name }}</strong></td>
                <td>${{ number_format($package->price) }}</td>
                <td>{{ $package->duration_days }} Days</td>
                <td>
                    <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-sm btn-info text-white">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    {{-- Standard Delete Form --}}
                    <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this safari?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>