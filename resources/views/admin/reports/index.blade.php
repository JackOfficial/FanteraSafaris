@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Business Analytics</h1>
        </div>
    </div>

    <div class="row">
        {{-- Stats Cards --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary text-white p-3 rounded shadow-sm">
                <div class="inner">
                    <h3>{{ $totalInquiries }}</h3>
                    <p>Total Safari Inquiries</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart Section --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="card-title"><i class="fas fa-chart-line me-2"></i> Monthly Inquiry Trends ({{ date('Y') }})</h3>
                </div>
                <div class="card-body">
                    <canvas id="inquiryChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js Script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('inquiryChart').getContext('2d');
    const inquiryChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Inquiries Received',
                data: {!! $chartData !!},
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4 // Makes the line smooth
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
</script>
@endsection