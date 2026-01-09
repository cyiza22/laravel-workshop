<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Order;
use App\Models\Product;
use App\Http\Requests\UpdateOrderStatusRequest;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        $ordersQuery = Order::with('items');

        if ($status) {
            $ordersQuery->where('status', $status);
        }

        $orders = $ordersQuery->paginate(10);

        /* ---------- Analytics ---------- */

        $analytics = [
            'total_orders'    => Order::count(),
            'pending_orders'  => Order::where('status', 'pending')->count(),
            'waiting_orders'  => Order::where('status', 'waiting')->count(),
            'delivered_orders'=> Order::where('status', 'delivered')->count(),
            'total_price'   => Order::with('items')->get()
                ->sum(fn ($order) => $order->total_price),
        ];

        return view('orders.index', [
            'orders'     => $orders,
            'analytics'  => $analytics,
            'status'     => $status,
        ]);
    }

    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        return view('orders.show', [
            'order' => $order,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(UpdatedOrderStatusRequest $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'delivered') {
            $order->update([
                'status' => $request->status,
            ]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully!');
    }

    /**
     * Restore deleted item.
     */
    public function restore(string $id)
    {
        $order = Order::withTrashed()->findOrFail($id);
        $order->restore();
        return redirect()->route('orders.show', ['id' => $order->id])
            ->with('success', 'Order restored successfully!');
    }
}
