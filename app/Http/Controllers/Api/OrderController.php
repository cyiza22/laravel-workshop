<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use App\Action\CreateOrderAction;
use App\Models\Order;
use App\Action\DeleteOrderAction;

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
    $orders = $this->orderService->fetchAllApi(
        $request,
        $request->user()->id
    );

    return OrderResource::collection($orders);
}

    /**
     * Store a newly created resource in storage.
     */

    //using order service to create order
    // public function store(Request $request)
    // {
    //     $data = $request->all();
    //     $data['user_id'] = $request->user()->id;

    //     $order = $this->orderService->create($data);

    //     return new OrderResource($order);
    // }

    //using action to create order
    public function store(Request $request, CreateOrderAction $action)
{
    $data = $request->all();
    $data['user_id'] = $request->user()->id;

    $order = $action->handle(
        new \App\CustomData\CreateOrderData($data)
    );

    return new OrderResource($order);
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = $request->user()->orders()->findOrFail($id);

        $this->orderService->updateStatus($order, $request->all());

        return new OrderResource($order->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */

    //using order service to delete order
    // public function destroy(Request $request, string $id)
    // {
    //     $order = $request->user()->orders()->findOrFail($id);

    //     $this->orderService->delete($order);

    //     return response()->json([
    //         'message' => 'Order deleted successfully.'
    //     ], 200);
    // }

    //using action to delete order
    public function destroy(Request $request, string $id, DeleteOrderAction $action)
    {
        $order = $request->user()->orders()->findOrFail($id);
        $action->handle($order);
        return response()->json([
            'message' => 'Order deleted successfully.'
        ], 200);
    }
}
