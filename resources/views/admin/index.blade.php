@extends('admin.layouts.app')

@section('title', 'Dashboard | FameOceans')

@section('content')
<div class="container-fluid">

    <!-- KPI Cards -->
    <div class="row">

        <!-- Total Creators -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ Auth::user()->count() ?? 0 }}</h3>
                    <p>Total Users</p>
                </div>
                <div class="icon"><i class="fas fa-user-check"></i></div>
                <a href="/admin/users" class="small-box-footer">
                    Manage Users <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Total Posts -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $posts ?? 0 }}</h3>
                    <p>Total Posts</p>
                </div>
                <div class="icon"><i class="fas fa-pen-nib"></i></div>
                <a href="/admin/posts" class="small-box-footer">
                    View Posts <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Pending Reviews -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $pendingPosts ?? 0 }}</h3>
                    <p>Pending Reviews</p>
                </div>
                <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                <a href="/admin/posts?status=pending" class="small-box-footer">
                    Review Content <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Reported Content -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $reportedPosts ?? 0 }}</h3>
                    <p>Reported Content</p>
                </div>
                <div class="icon"><i class="fas fa-flag"></i></div>
                <a href="/admin/reports" class="small-box-footer">
                    View Reports <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

    </div>

    <!-- Charts Row -->
    <div class="row">

        <!-- Growth Chart -->
        <div class="col-md-8">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Platform Growth Overview</h3>
                </div>
                <div class="card-body">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Content Distribution -->
        <div class="col-md-4">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Content Distribution</h3>
                </div>
                <div class="card-body">
                    <canvas id="contentChart"></canvas>
                </div>
            </div>
        </div>

    </div>

    <!-- Recent Activity & Quick Access -->
    <div class="row">

        <!-- Recent Posts -->
        <div class="col-md-8">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Recent Posts</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Creator</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentPosts ?? [] as $post)
                                <tr>
                                    <td>{{ $post->title ?? '—' }}</td>
                                    <td>{{ $post->user?->name ?? '—' }}</td>
                                    <td>
                                        <span class="badge
                                            @if(($post->status ?? '') === 'pending') badge-warning
                                            @elseif(($post->status ?? '') === 'published') badge-success
                                            @else badge-secondary @endif">
                                            {{ ucfirst($post->status ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td>{{ $post->created_at?->format('d M Y') ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted">
                                        No recent activity
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Access -->
        <div class="col-md-4">
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Quick Access</h3>
                </div>
                <div class="card-body">
                    <a href="/admin/posts" class="btn btn-app bg-info">
                        <i class="fas fa-pen"></i> Posts
                    </a>
                    <a href="/admin/categories" class="btn btn-app bg-warning">
                        <i class="fas fa-tags"></i> Categories
                    </a>
                    <a href="/admin/users" class="btn btn-app bg-primary">
                        <i class="fas fa-users"></i> Users
                    </a>
                    <a href="/admin/reports" class="btn btn-app bg-danger">
                        <i class="fas fa-flag"></i> Reports
                    </a>
                    <a href="/admin/settings" class="btn btn-app bg-success">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Growth Chart
    new Chart(document.getElementById('growthChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($growthMonths ?? ['Jan','Feb','Mar','Apr']) !!},
            datasets: [{
                label: 'New Users',
                data: {!! json_encode($growthData ?? [10,20,15,30]) !!},
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13,110,253,0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: true } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Content Distribution
    new Chart(document.getElementById('contentChart'), {
        type: 'doughnut',
        data: {
            labels: ['Published', 'Pending', 'Draft'],
            datasets: [{
                data: {!! json_encode($contentStats ?? [5,3,2]) !!},
                backgroundColor: ['#28a745', '#ffc107', '#6c757d']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endsection