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
        $showDeleted = $request->query('show_deleted', '0');

        $ordersQuery = Order::with('orderItems');

        if ($status && $status!== 'all') {
            $ordersQuery->where('status', $status);
        }

        // Handle showing deleted orders
        if ($showDeleted === '1') {
            $ordersQuery->onlyTrashed(); // Show only soft-deleted orders
        }

        $orders = $ordersQuery->paginate(10);

        /* ---------- Analytics ---------- */
        // For analytics, we should consider active orders only (not trashed)
        $analytics = [
            'total_orders'    => Order::count(),
            'pending_orders'  => Order::with('status', 'pending')->count(),
            'waiting_orders'  => Order::with('status', 'waiting')->count(),
            'delivered_orders'=> Order::with('status', 'delivered')->count(),
            'total_price'     => Order::with('orderItems')->get()
                ->sum(fn ($order) => $order->total_price),
        ];

        return view('orders.index', [
            'orders'        => $orders,
            'analytics'     => $analytics,
            'current_filter'=> $status ?: 'all',
            'show_deleted'  => $showDeleted, 
        ]);
    }

    public function show($id)
{
    $order = Order::with('orderItems.product')->findOrFail($id);
    $products = Product::all(); 

    return view('orders.show', [
        'order' => $order,
        'products' => $products,
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
    public function updateStatus(UpdateOrderStatusRequest $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'delivered') {
            $order->update([
                'status' => $request->status,
            ]);
        }

        return redirect()->route('orders.show', ['id' => $order->id])
            ->with('success', 'Order status updated successfully!');
          
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
