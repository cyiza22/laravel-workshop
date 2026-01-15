<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Order;
use App\Models\Product;
use App\Http\Requests\UpdateOrderStatusRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\OrderService;



class OrderController extends Controller
{

    public function __construct(protected OrderService $orderService)
    {
    }
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
{
    $data = $this->orderService->fetchAll($request, auth()->id());
    return view('orders.index', [
        'orders' => $data['orders'],
        'analytics' => $data['analytics'],
        'current_filter' => $data['status'],
        'show_deleted' => $data['show_deleted'],
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
        // $order = Order::where('id', $id)
        //     ->where('user_id', auth()->id())
        //     ->firstOrFail();

        // if ($order->status !== 'delivered') {
        //     $order->update([
        //         'status' => $request->status,
        //     ]);
        // }

        $order = $this->authorize('update', Order::findOrFail($id));
        return redirect()->route('orders.show', ['id' => $order->id])
            ->with('success', 'Order status updated successfully!');
          
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $order = Order::where('id', $id)
        //     ->where('user_id', auth()->id())
        //     ->firstOrFail();
        $order = $this->authorize('delete', Order::findOrFail($id));

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
