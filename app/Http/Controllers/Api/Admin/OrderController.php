<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->withCount('items');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10);

        return response()->json([
            'data' => $orders->getCollection()->map(fn($o) => $this->format($o)),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page'    => $orders->lastPage(),
                'from'         => $orders->firstItem(),
                'to'           => $orders->lastItem(),
                'total'        => $orders->total(),
            ],
        ]);
    }

    public function show(string $id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);

        return response()->json([
            'id'               => $order->id,
            'order_number'     => $order->order_number,
            'status'           => $order->status,
            'status_label'     => $order->status_label,
            'total'            => $order->total,
            'notes'            => $order->notes,
            'shipping_address' => $order->shipping_address,
            'phone'            => $order->phone,
            'created_at'       => $order->created_at->format('d/m/Y H:i'),
            'user'             => ['id' => $order->user?->id, 'name' => $order->user?->name, 'email' => $order->user?->email],
            'items'            => $order->items->map(fn($item) => [
                'id'         => $item->id,
                'quantity'   => $item->quantity,
                'unit_price' => $item->unit_price,
                'subtotal'   => $item->subtotal,
                'product'    => [
                    'id'        => $item->product?->id,
                    'name'      => $item->product?->name,
                    'image_url' => $item->product?->image_url,
                    'weight'    => $item->product?->weight,
                ],
            ]),
        ]);
    }

    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,delivered,cancelled',
        ], [
            'status.required' => 'El estado es obligatorio.',
            'status.in'       => 'El estado no es válido.',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return response()->json([
            'message'      => 'Estado del pedido actualizado.',
            'order_number' => $order->order_number,
            'status'       => $order->status,
            'status_label' => $order->status_label,
        ]);
    }

    private function format(Order $o): array
    {
        return [
            'id'           => $o->id,
            'order_number' => $o->order_number,
            'status'       => $o->status,
            'status_label' => $o->status_label,
            'total'        => $o->total,
            'items_count'  => $o->items_count ?? 0,
            'created_at'   => $o->created_at->format('d/m/Y H:i'),
            'user'         => ['name' => $o->user?->name, 'email' => $o->user?->email],
        ];
    }
}
