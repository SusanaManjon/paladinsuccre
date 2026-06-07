<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return response()->json([
            'data' => $orders->getCollection()->map(fn($o) => [
                'id'           => $o->id,
                'order_number' => $o->order_number,
                'status'       => $o->status,
                'status_label' => $o->status_label,
                'total'        => $o->total,
                'items_count'  => $o->items_count,
                'created_at'   => $o->created_at->format('d/m/Y H:i'),
            ]),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page'    => $orders->lastPage(),
                'from'         => $orders->firstItem(),
                'to'           => $orders->lastItem(),
                'total'        => $orders->total(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'            => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'phone'            => 'required|string|max:20',
            'notes'            => 'nullable|string',
            'payment_method'   => 'nullable|string',
            'paypal_transaction_id' => 'nullable|string',
        ], [
            'items.required'              => 'Debes agregar al menos un producto.',
            'items.*.product_id.exists'   => 'Uno de los productos no existe.',
            'items.*.quantity.min'        => 'La cantidad mínima es 1.',
            'shipping_address.required'   => 'La dirección de envío es obligatoria.',
            'phone.required'              => 'El teléfono es obligatorio.',
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if (!$product->is_active) {
                    DB::rollBack();
                    return response()->json(['message' => "El producto '{$product->name}' no está disponible."], 422);
                }

                if ($product->stock < $item['quantity']) {
                    DB::rollBack();
                    return response()->json(['message' => "Stock insuficiente para '{$product->name}'. Disponible: {$product->stock}."], 422);
                }

                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal'   => $subtotal,
                ];

                $product->decrement('stock', $item['quantity']);
            }

            $status = 'pending';
            $paymentStatus = 'pending';
            $notes = $request->notes;

            if ($request->payment_method === 'paypal' && $request->paypal_transaction_id) {
                $status = 'confirmed';
                $paymentStatus = 'paid';
            }

            $order = Order::create([
                'user_id'          => $request->user()->id,
                'total'            => $total,
                'status'           => $status,
                'shipping_address' => $request->shipping_address,
                'phone'            => $request->phone,
                'notes'            => $notes,
                'payment_method'   => $request->payment_method,
                'payment_status'   => $paymentStatus,
                'transaction_id'   => $request->paypal_transaction_id,
            ]);

            foreach ($orderItems as $itemData) {
                $order->items()->create($itemData);
            }

            DB::commit();

            return response()->json([
                'message'      => 'Pedido creado correctamente.',
                'id'           => $order->id,
                'order_number' => $order->order_number,
                'total'        => $order->total,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al procesar el pedido. Inténtalo de nuevo.'], 500);
        }
    }

    public function show(Request $request, string $id)
    {
        $order = Order::where('user_id', $request->user()->id)
            ->with('items.product')
            ->findOrFail($id);

        return response()->json([
            'id'                => $order->id,
            'order_number'      => $order->order_number,
            'status'            => $order->status,
            'status_label'      => $order->status_label,
            'total'             => $order->total,
            'notes'             => $order->notes,
            'shipping_address'  => $order->shipping_address,
            'phone'             => $order->phone,
            'created_at'        => $order->created_at->format('d/m/Y H:i'),
            'payment_method'    => $order->payment_method,
            'payment_status'    => $order->payment_status,
            'payment_proof_url' => $order->payment_proof_url,
            'items'             => $order->items->map(fn($item) => [
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

    public function uploadPaymentProof(Request $request, string $id)
    {
        $request->validate([
            'payment_proof' => 'required|mimes:jpeg,png,jpg,gif,webp,heic,pdf|max:20480', // 20MB max, allow heic and pdf
        ]);

        $order = Order::where('user_id', $request->user()->id)->findOrFail($id);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payments', 'public');
            $order->payment_proof = $path;
            $order->save();

            return response()->json([
                'message' => 'Comprobante subido correctamente.',
                'payment_proof_url' => $order->payment_proof_url,
            ]);
        }

        return response()->json(['message' => 'Error al subir el comprobante.'], 500);
    }
}
