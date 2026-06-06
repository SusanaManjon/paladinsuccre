<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts   = Product::count();
        $totalCategories = Category::count();
        $totalOrders     = Order::count();
        $totalClients    = User::where('role', 'client')->count();
        $pendingOrders   = Order::where('status', 'pending')->count();

        $recentOrders = Order::with(['user', 'items.product'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn($o) => [
                'id'           => $o->id,
                'order_number' => $o->order_number,
                'status'       => $o->status,
                'status_label' => $o->status_label,
                'total'        => $o->total,
                'created_at'   => $o->created_at->format('d/m/Y H:i'),
                'user'         => ['name' => $o->user?->name, 'email' => $o->user?->email],
                'items_count'  => $o->items->count(),
            ]);

        return response()->json([
            'total_products'   => $totalProducts,
            'total_categories' => $totalCategories,
            'total_orders'     => $totalOrders,
            'total_clients'    => $totalClients,
            'pending_orders'   => $pendingOrders,
            'recent_orders'    => $recentOrders,
        ]);
    }
}
