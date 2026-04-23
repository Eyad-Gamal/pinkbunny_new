<?php

namespace App\Services;

use App\Models\{Order, Product, User, VisitorLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Cache, DB};
use Jenssegers\Agent\Agent;

class VisitorTrackingService
{
    public function track(Request $request): void
    {
        $agent = new Agent();
        $agent->setUserAgent($request->userAgent());

        VisitorLog::create([
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent(),
            'page_url'     => $request->url(),
            'referer'      => $request->header('referer'),
            'browser'      => $agent->browser(),
            'platform'     => $agent->platform(),
            'device_type'  => $agent->isMobile() ? 'mobile' : ($agent->isTablet() ? 'tablet' : 'desktop'),
            'user_id'      => auth()->id(),
            'visited_date' => now()->toDateString(),
        ]);
    }

    public function getDashboardStats(): array
    {
        return Cache::remember('dashboard_stats', 300, function () {
            $today     = now()->toDateString();
            $yesterday = now()->subDay()->toDateString();
            $thisMonth = now()->startOfMonth();

            return [
                'visitors_today'     => VisitorLog::where('visited_date', $today)->distinct('ip_address')->count('ip_address'),
                'visitors_yesterday' => VisitorLog::where('visited_date', $yesterday)->distinct('ip_address')->count('ip_address'),
                'visitors_month'     => VisitorLog::where('visited_date', '>=', $thisMonth)->distinct('ip_address')->count('ip_address'),
                'pageviews_today'    => VisitorLog::where('visited_date', $today)->count(),

                'total_users'     => User::count(),
                'new_users_today' => User::whereDate('created_at', $today)->count(),
                'new_users_month' => User::where('created_at', '>=', $thisMonth)->count(),

                'orders_today'    => Order::whereDate('created_at', $today)->count(),
                'orders_month'    => Order::where('created_at', '>=', $thisMonth)->count(),
                'pending_orders'  => Order::where('status', 'pending')->count(),
                'revenue_today'   => Order::whereDate('created_at', $today)->where('payment_status', 'paid')->sum('total_amount'),
                'revenue_month'   => Order::where('created_at', '>=', $thisMonth)->where('payment_status', 'paid')->sum('total_amount'),
                'revenue_total'   => Order::where('payment_status', 'paid')->sum('total_amount'),

                'total_products'     => Product::active()->count(),
                'low_stock_products' => Product::whereColumn('stock_quantity', '<=', 'low_stock_threshold')->where('stock_quantity', '>', 0)->count(),
                'out_of_stock'       => Product::where('stock_quantity', 0)->count(),

                'visitors_chart'   => $this->getVisitorsChart(),
                'revenue_chart'    => $this->getRevenueChart(),
                'orders_by_status' => $this->getOrdersByStatus(),
                'device_breakdown' => $this->getDeviceBreakdown(),
                'top_products'     => $this->getTopProducts(),
                'recent_orders'    => $this->getRecentOrders(),
            ];
        });
    }

    private function getVisitorsChart(): array
    {
        return VisitorLog::select(
            DB::raw('visited_date as date'),
            DB::raw('COUNT(DISTINCT ip_address) as unique_visitors'),
            DB::raw('COUNT(*) as pageviews')
        )
        ->where('visited_date', '>=', now()->subDays(30))
        ->groupBy('visited_date')
        ->orderBy('visited_date')
        ->get()->toArray();
    }

    private function getRevenueChart(): array
    {
        return Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as revenue'),
            DB::raw('COUNT(*) as orders_count')
        )
        ->where('created_at', '>=', now()->subDays(30))
        ->where('payment_status', 'paid')
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('date')
        ->get()->toArray();
    }

    private function getOrdersByStatus(): array
    {
        return Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')->pluck('count', 'status')->toArray();
    }

    private function getDeviceBreakdown(): array
    {
        return VisitorLog::select('device_type', DB::raw('COUNT(*) as count'))
            ->where('visited_date', '>=', now()->subDays(30))
            ->groupBy('device_type')->pluck('count', 'device_type')->toArray();
    }

    private function getTopProducts(): array
    {
        return Product::with('brand')
            ->orderByDesc('total_sold')->limit(10)
            ->get(['id', 'name_en', 'name_ar', 'total_sold', 'price', 'brand_id'])->toArray();
    }

    private function getRecentOrders(): array
    {
        return Order::with(['user', 'items'])->latest()->limit(10)->get()->toArray();
    }
}
