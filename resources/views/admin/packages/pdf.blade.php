<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Safari Packages Inventory</title>
    <style>
        /* Modern reset and typography */
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 11px; 
            color: #333; 
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        /* Header Layout */
        .header-container {
            padding: 20px 0;
            border-bottom: 2px solid #007bff; /* Primary Accent */
            margin-bottom: 30px;
        }

        .logo {
            float: left;
            height: 60px; /* Adjust based on your logo aspect ratio */
        }

        .company-info {
            float: right;
            text-align: right;
        }

        .company-info h1 {
            margin: 0;
            font-size: 22px;
            color: #007bff;
            text-transform: uppercase;
        }

        .clear { clear: both; }

        /* Table Styling */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }

        th { 
            background-color: #f8f9fa; 
            color: #555;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            border-bottom: 1px solid #dee2e6;
            padding: 12px 8px;
            text-align: left;
        }

        td { 
            padding: 10px 8px; 
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        /* Zebra Striping */
        tr:nth-child(even) { background-color: #fafafa; }

        /* Custom Classes */
        .id-badge {
            color: #888;
            font-family: monospace;
        }

        .package-name {
            font-weight: bold;
            font-size: 12px;
            color: #222;
        }

        .destination-tag {
            color: #007bff;
            font-weight: 500;
        }

        .price-cell { 
            font-weight: bold; 
            font-size: 13px;
            color: #28a745; /* Green for pricing */
            text-align: right;
        }

        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header-container">
        <div class="logo">
            {{-- Option A: If logo is in public folder --}}
            <img src="{{ public_path('front/images/FanteraSafaris_logo.png') }}" alt="Logo" style="height: 50px;">
            
            {{-- Option B: Base64 (Reliable for some servers) --}}
            {{-- <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" /> --}}
        </div>
        <div class="company-info">
            <h1>Safari Packages</h1>
            <p>
                Inventory Report<br>
                Date: {{ now()->format('M d, Y') }}
            </p>
        </div>
        <div class="clear"></div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="35%">Package & Category</th>
                <th width="20%">Destination</th>
                <th width="15%">Duration</th>
                <th width="25%" style="text-align: right;">Price (USD)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($packages as $package)
            <tr>
                <td class="id-badge">#{{ str_pad($package->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td>
                    <div class="package-name">{{ $package->name }}</div>
                    <small style="color: #777;">{{ $package->category->name ?? 'Standard' }}</small>
                </td>
                <td class="destination-tag">
                    {{ $package->destination->name ?? 'TBA' }}
                </td>
                <td>{{ $package->duration_days }} Days / {{ $package->duration_days - 1 }} Nights</td>
                <td class="price-cell">${{ number_format($package->price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Confidential Document - {{ config('app.name') }} &copy; {{ date('Y') }} - Page 1
    </div>

</body>
</html>