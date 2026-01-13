<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Order;
use App\Models\Product;
use App\Http\Requests\UpdateOrderStatusRequest;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
{
    $status = $request->query('status');
    $showDeleted = $request->query('show_deleted', '0');

    $ordersQuery = Order::where('user_id', auth()->id())
        ->with('orderItems')
        ->withCount('orderItems');

    if ($status && $status !== 'all') {
        $ordersQuery->where('status', $status);
    }

    if ($showDeleted === '1') {
        $ordersQuery->onlyTrashed();
    }

    $orders = $ordersQuery->paginate(10);
    $totalPrice = Order::where('user_id', auth()->id())
        ->with('orderItems')
        ->get()
        ->sum(function ($order) {
            return $order->orderItems->sum(function ($item) {
                return $item->unit_price * $item->quantity;
            });
        });

    $analytics = [
        'total_orders'     => Order::where('user_id', auth()->id())->count(),
        'pending_orders'   => Order::where('user_id', auth()->id())->where('status', 'pending')->count(),
        'waiting_orders'   => Order::where('user_id', auth()->id())->where('status', 'waiting')->count(),
        'delivered_orders' => Order::where('user_id', auth()->id())->where('status', 'delivered')->count(),
        'total_price'    => $totalPrice,
        
    ];

    return view('orders.index', [
        'orders' => $orders,
        'analytics' => $analytics,
        'current_filter' => $status ?: 'all',
        'show_deleted' => $showDeleted,
    ]);
}

    public function show($id)
{
    $order = Order::where('id', $id)
            ->where('user_id', auth()->id())
            ->with('orderItems.product')
            ->firstOrFail();

        $products = Product::all();

        return view('orders.show', [
            'order' => $order,
            'products' => $products,
            'user_id' => auth()->id(),
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
        $order = Order::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

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
        $order = Order::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully!');
    }

    /**
     * Restore deleted item.
     */
    public function restore(string $id)
    {
        $order = Order::withTrashed()
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $order->restore();

        return redirect()->route('orders.show', ['id' => $order->id])
            ->with('success', 'Order restored successfully!');
    }
}
