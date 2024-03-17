<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Midtrans\CreateVAService;

class OrderController extends Controller
{
    // function for order
    public function order(Request $request)
    {
        // validate request
        $request->validate([
            'address_id' => 'required',
            'payment_method' => 'required',
            'shipping_service' => 'nullable',
            'shipping_cost' => 'nullable',
            'total_cost' => 'required',
            'items' => 'required',
        ]);

        $subtotal = 0;
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $subtotal += $product->price * $item['quantity'];
        }

        $order = Order::create([
            'user_id' => $request->user()->id,
            'address_id' => $request->address_id,
            'subtotal' => $subtotal,
            'shipping_cost' => $request->shipping_cost,
            'total_cost' => $subtotal + $request->shipping_cost,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'shipping_service' => $request->shipping_service,
            'transaction_number' => 'TRX' . rand(100000, 999999),
        ]);

        // if payamen_va_name is not null
        if ($request->payment_va_name) {
            $order->update([
                'payment_va_name' => $request->payment_va_name,
            ]);
        }
        // create order items
        foreach ($request->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        // request ke midtrans
        $midtrans = new CreateVAService($order->load('user', 'orderItems'));
        $apiResponse = $midtrans->getVA();

        $order->payment_va_number = $apiResponse->va_numbers[0]->va_number;
        $order->save();

        return response()->json([
            'message'  => 'Order created successfully',
            'order' => $order,
        ]);
    }
}
