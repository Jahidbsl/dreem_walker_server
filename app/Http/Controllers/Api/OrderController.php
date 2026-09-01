<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping.name' => 'required|string|max:255',
            'shipping.phone' => 'required|string|max:20',
            'shipping.address' => 'required|string',
            'shipping.city' => 'required|string|max:100',
            'shipping.note' => 'nullable|string',

            'payment_method' => 'required|string|in:cod',

            'items' => 'required|array|min:1',
            'items.*.variant_id' => 'required|integer|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',

            'total' => 'required|numeric|min:0',
        ]);

        $order = DB::transaction(function () use ($validated, $request) {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'name' => $validated['shipping']['name'],
                'phone' => $validated['shipping']['phone'],
                'address' => $validated['shipping']['address'],
                'city' => $validated['shipping']['city'],
                'note' => $validated['shipping']['note'] ?? null,
                'payment_method' => $validated['payment_method'],
                'total' => $validated['total'],
                'status' => 'pending',
            ]);

            foreach ($validated['items'] as $item) {
                $variant = ProductVariant::lockForUpdate()->findOrFail($item['variant_id']);

                if ($variant->stock < $item['quantity']) {
                    abort(422, "Not enough stock for variant #{$variant->id}");
                }

                $order->items()->create([
                    'variant_id' => $item['variant_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                $variant->decrement('stock', $item['quantity']);
            }

            return $order;
        });

        return response()->json([
            'status' => true,
            'message' => 'Order placed successfully',
            'data' => $order->load('items.variant'),
        ], 201);
    }

    public function index(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->with('items.variant')
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $orders,
        ]);
    }

    public function show(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized');
        }

        return response()->json([
            'status' => true,
            'data' => $order->load('items.variant'),
        ]);
    }
}