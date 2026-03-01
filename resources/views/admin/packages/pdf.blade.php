<!DOCTYPE html>
<html>
<head>
    <title>Safari Packages List</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; }
        .price { font-weight: bold; color: #2c3e50; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Safari Packages Inventory</h2>
        <p>Generated on: {{ now()->format('F d, Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Package Name</th>
                <th>Destination</th>
                <th>Category</th>
                <th>Duration</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($packages as $package)
            <tr>
                <td>{{ $package->id }}</td>
                <td>{{ $package->name }}</td>
                <td>{{ $package->destination->name ?? 'N/A' }}</td>
                <td>{{ $package->category->name ?? 'N/A' }}</td>
                <td>{{ $package->duration_days }} Days</td>
                <td class="price">${{ number_format($package->price) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>