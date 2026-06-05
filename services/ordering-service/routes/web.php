<?php

use App\Jobs\ProcessOrderPlaced;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/api/orders/test-dispatch', function () {
    $orderData = [
        'id' => (string) Str::uuid(),
        'item' => request('item', 'MacBook Pro'),
        'price' => (float) request('price', 2499),
        'placed_at' => now()->toISOString(),
    ];

    ProcessOrderPlaced::dispatch($orderData)->onConnection('rabbitmq')->onQueue('order_queue');

    return response()->json([
        'message' => 'OrderPlaced job dispatched to RabbitMQ.',
        'queue' => 'order_queue',
        'routing_key' => 'order.placed',
        'order' => $orderData,
    ]);
});

Route::get('/', function () {
    return view('welcome');
});
