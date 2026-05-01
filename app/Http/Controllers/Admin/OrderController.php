<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\{OrderService, WhatsAppService};
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private OrderService   $orderService,
        private WhatsAppService $whatsApp,
    ) {}

    public function index(Request $request)
    {
        $orders = Order::with(['user', 'items'])
            ->when($request->status,  fn($q) => $q->where('status', $request->status))
            ->when($request->search,  fn($q, $s) =>
                $q->where('order_number', 'like', "%{$s}%")
                  ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$s}%"))
            )
            ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to,   fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $statusCounts = Order::selectRaw('status, COUNT(*) as count')->groupBy('status')->pluck('count', 'status');

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    public function show(string $id)
    {
        $order = Order::with(['user', 'items.product', 'address', 'tracking.updater'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function invoice(string $id)
    {
        $order = Order::with(['user', 'items.product', 'address'])->findOrFail($id);
        return view('admin.orders.invoice', compact('order'));
    }

    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status'           => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded',
            'tracking_number'  => 'nullable|string|max:100',
            'shipping_company' => 'nullable|string|max:100',
            'admin_notes'      => 'nullable|string|max:1000',
        ]);

        $order = Order::findOrFail($id);

        $this->orderService->updateStatus($order, $request->status, auth()->id(), $request->only(
            'tracking_number', 'shipping_company', 'admin_notes'
        ));

        if (in_array($request->status, ['shipped', 'delivered'])) {
            $this->whatsApp->sendOrderStatusToCustomer($order->fresh());
        }

        return back()->with('toast', "Order status updated to: {$order->fresh()->status_label}");
    }

    public function resendWhatsApp(string $id)
    {
        $order = Order::with(['user', 'items', 'address'])->findOrFail($id);

        $sent = $this->whatsApp->sendOrderConfirmation($order);

        if ($sent) {
            return back()->with('toast', "WhatsApp confirmation resent for order #{$order->order_number}");
        }

        return back()->with('error', 'Failed to send WhatsApp message. Check logs for details.');
    }

    public function export(Request $request)
    {
        $orders = Order::with(['user', 'items.product', 'address'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, fn($q, $s) =>
                $q->where('order_number', 'like', "%{$s}%")
                  ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$s}%"))
            )
            ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to,   fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->latest()
            ->get();

        // Build Excel XML (SpreadsheetML) for native .xlsx-like support
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"';
        $xml .= ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">';
        $xml .= '<Styles>';
        $xml .= '<Style ss:ID="header"><Font ss:Bold="1" ss:Size="11"/><Interior ss:Color="#F472B6" ss:Pattern="Solid"/><Font ss:Color="#FFFFFF" ss:Bold="1"/></Style>';
        $xml .= '<Style ss:ID="currency"><NumberFormat ss:Format="#,##0.00"/></Style>';
        $xml .= '<Style ss:ID="date"><NumberFormat ss:Format="yyyy-mm-dd hh:mm"/></Style>';
        $xml .= '</Styles>';

        $xml .= '<Worksheet ss:Name="Orders">';
        $xml .= '<Table>';

        // Header row
        $headers = ['Order Number', 'Customer Name', 'Customer Email', 'Customer Phone',
                     'Shipping Name', 'Shipping Phone', 'Address', 'City', 'Governorate',
                     'Products', 'Items Count', 'Subtotal', 'Shipping Fee', 'Discount',
                     'Coupon', 'Total', 'Payment Method', 'Status', 'Date', 'Notes'];
        $xml .= '<Row>';
        foreach ($headers as $h) {
            $xml .= '<Cell ss:StyleID="header"><Data ss:Type="String">' . htmlspecialchars($h) . '</Data></Cell>';
        }
        $xml .= '</Row>';

        // Data rows
        foreach ($orders as $order) {
            $productsStr = $order->items->map(fn($i) => "{$i->product_name_en} x{$i->quantity}")->implode(', ');

            $xml .= '<Row>';
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($order->order_number) . '</Data></Cell>';
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($order->user->name) . '</Data></Cell>';
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($order->user->email) . '</Data></Cell>';
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($order->user->phone ?? '') . '</Data></Cell>';
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($order->address?->full_name ?? '') . '</Data></Cell>';
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($order->address?->phone ?? '') . '</Data></Cell>';
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($order->address?->street ?? '') . '</Data></Cell>';
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($order->address?->city ?? '') . '</Data></Cell>';
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($order->address?->governorate ?? '') . '</Data></Cell>';
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($productsStr) . '</Data></Cell>';
            $xml .= '<Cell><Data ss:Type="Number">' . $order->items->sum('quantity') . '</Data></Cell>';
            $xml .= '<Cell ss:StyleID="currency"><Data ss:Type="Number">' . $order->subtotal . '</Data></Cell>';
            $xml .= '<Cell ss:StyleID="currency"><Data ss:Type="Number">' . $order->shipping_fee . '</Data></Cell>';
            $xml .= '<Cell ss:StyleID="currency"><Data ss:Type="Number">' . $order->discount_amount . '</Data></Cell>';
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($order->coupon_code ?? '') . '</Data></Cell>';
            $xml .= '<Cell ss:StyleID="currency"><Data ss:Type="Number">' . $order->total_amount . '</Data></Cell>';
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars(ucfirst(str_replace('_', ' ', $order->payment_method))) . '</Data></Cell>';
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars(ucfirst($order->status)) . '</Data></Cell>';
            $xml .= '<Cell><Data ss:Type="String">' . $order->created_at->format('Y-m-d H:i') . '</Data></Cell>';
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($order->notes ?? '') . '</Data></Cell>';
            $xml .= '</Row>';
        }

        $xml .= '</Table></Worksheet></Workbook>';

        $filename = 'orders-' . now()->format('Y-m-d') . '.xls';

        return response($xml, 200, [
            'Content-Type'        => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control'       => 'max-age=0',
        ]);
    }
}
