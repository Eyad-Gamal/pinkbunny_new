<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\VisitorTrackingService;

class DashboardController extends Controller
{
    public function __construct(private VisitorTrackingService $tracker) {}

    public function index()
    {
        $stats = $this->tracker->getDashboardStats();
        return view('admin.dashboard', compact('stats'));
    }
}
