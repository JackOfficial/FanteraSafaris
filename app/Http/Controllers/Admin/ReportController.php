<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use App\Models\Post;

class ReportController extends Controller
{
public function index()
{
    // Get inquiry counts grouped by month for the current year
    $inquiryData = ContactMessage::selectRaw('COUNT(*) as count, MONTH(created_at) as month')
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->pluck('count', 'month')
        ->toArray();

    // Ensure all 12 months are represented, even with 0
    $chartData = [];
    for ($i = 1; $i <= 12; $i++) {
        $chartData[] = $inquiryData[$i] ?? 0;
    }

    return view('admin.reports.index', [
        'totalPosts' => Post::count(),
        'totalInquiries' => ContactMessage::count(),
        'chartData' => json_encode($chartData)
    ]);
}
}
