<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return view('admin.order.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        Order::create($data);
        return redirect('/admin/order');
    }

    public function update(Request $request, $id)
    {
        $order = DB::table('orders')->where('id', $id);
        $order->update([
            'status' => $request->status,
            'shipping_resi' => $request->shipping_resi
        ]);
        return redirect('/admin/order');
    }

    public function destroy($id)
    {
        Order::findOrFail($id)->delete();
        return  redirect('/admin/order');
    }
}
