<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Find the client user
$client = \App\Models\User::where('role', 'client')->first();
echo "Client user: " . ($client ? $client->id . ' - ' . $client->email : 'NONE') . "\n";

// Find orders for this client
$orders = \App\Models\Order::where('user_id', $client->id)->get();
echo "Orders count: " . $orders->count() . "\n";

foreach ($orders as $order) {
    echo "Order #{$order->id}: {$order->order_number} - status:{$order->status} - payment_method:{$order->payment_method} - payment_status:{$order->payment_status}\n";
}

// Try loading a single order with items
if ($orders->count() > 0) {
    $firstOrder = \App\Models\Order::with('items.product')
        ->where('user_id', $client->id)
        ->first();
    
    echo "\nFirst order detail:\n";
    echo json_encode([
        'id' => $firstOrder->id,
        'order_number' => $firstOrder->order_number,
        'status' => $firstOrder->status,
        'status_label' => $firstOrder->status_label,
        'total' => $firstOrder->total,
        'payment_method' => $firstOrder->payment_method,
        'payment_status' => $firstOrder->payment_status,
        'payment_proof_url' => $firstOrder->payment_proof_url,
        'items_count' => $firstOrder->items->count(),
        'items' => $firstOrder->items->map(fn($item) => [
            'id' => $item->id,
            'quantity' => $item->quantity,
            'unit_price' => $item->unit_price,
            'subtotal' => $item->subtotal,
            'product' => [
                'id' => $item->product?->id,
                'name' => $item->product?->name,
            ],
        ]),
    ], JSON_PRETTY_PRINT);
}

// Check if the user has a Sanctum token
$tokens = $client->tokens;
echo "\n\nClient tokens count: " . $tokens->count() . "\n";
