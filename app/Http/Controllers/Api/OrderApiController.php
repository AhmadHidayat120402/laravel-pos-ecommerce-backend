<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Midtrans\CreateVAService;

class OrderApiController extends Controller
{
    public function orderApi(Request $request)
    {
        // validate request
        $request->validate([
            'address_id' => 'required',
            'payment_method' => 'required',
            'shipping_service' => 'nullable',
            'shipping_cost' => 'nullable',
            'total_cost' => 'nullable',
            'items' => 'required|array',
        ]);

        $subtotal = 0;
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $subtotal += $product->price * $item['quantity'];
        }

        $jumlahDiskon = (($request->discount) / 100 * $subtotal);

        $order = Order::create([
            'payment_amount' => $request->payment_amount,
            'tax' => $request->tax,
            'discount' => $request->discount,
            'service_charge' => $request->service_charge,
            'payment_method' => $request->payment_method,
            'total_item' => $request->total_item,
            'id_kasir' => $request->id_kasir,
            'nama_kasir' => $request->nama_kasir,
            'transaction_time' => $request->transaction_time,
            'user_id' => $request->user()->id,
            'address_id' => $request->address_id,
            'subtotal' => $subtotal,
            'shipping_cost' => $request->shipping_cost,
            'total_cost' => $subtotal + $request->shipping_cost - $jumlahDiskon,
            'status' => 'pending',
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
        ], 200);
    }

    public function orderApiPos(Request $request)
    {

        $request->validate([
            'payment_amount' => 'required',
            'subtotal' => 'required',
            'tax' => 'required',
            'discount' => 'required',
            'service_charge' => 'required',
            'total_cost' => 'nullable',
            'payment_method' => 'required',
            'total_item' => 'required',
            'id_kasir' => 'required',
            'nama_kasir' => 'required',
            'order_items.*.id_product' => 'required|exists:products,id',
            'transaction_time' => 'required',
            'order_items' => 'required'
        ]);

        $jumlahDiskon = (($request->discount) / 100 * $request->subtotal);
        //create order
        $order = Order::create([
            'user_id' => $request->user()->id,
            'address_id' => $request->address_id,
            'payment_amount' => $request->payment_amount,
            'subtotal' => $request->subtotal,
            'tax' => $request->tax,
            'discount' => $request->discount,
            'service_charge' => $request->service_charge,
            'total_cost' => $request->subtotal - $jumlahDiskon,
            'payment_method' => $request->payment_method,
            'total_item' => $request->total_item,
            'id_kasir' => $request->id_kasir,
            'nama_kasir' => $request->nama_kasir,
            'status' => 'delivered',
            'transaction_time' => $request->transaction_time
        ]);

        //create order items
        foreach ($request->order_items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id_product'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $order
        ], 200);
    }

    public function getOrderById($id)
    {
        $order = Order::with('orderItems.product')->find($id);
        $order->load('user', 'address');
        return response()->json([
            'order' => $order,
        ]);
    }

    public function checkStatusOrder($id)
    {
        $order = Order::find($id);
        return response()->json([
            'status' => $order->status,
        ]);
    }
    // function for get all order by user
    public function getOrderByUser(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)->get();

        return response()->json([
            'orders' => $orders,
        ]);
    }
}
