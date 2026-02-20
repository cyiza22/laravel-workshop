<?php

namespace App\Http\Controllers\Api;

use App\Action\CreateOrderAction;
use App\Action\DeleteOrderAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\userResource;
use App\Models\Order;
use App\Services\NotificationAuthService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
    // public function update(Request $request, string $id)
    // {
    //     $order = $request->user()->orders()->findOrFail($id);

    //     $this->orderService->updateStatus($order, $request->all());

    //     return new OrderResource($order->fresh());
    // }

    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);
        $this->authorize('update', $order);
        $this->orderService->updateStatus($order, $request->all());
        $user = $order->user;
        $data = (new userResource($user))->resolve();

        // 3. Send to Service 2 (The Notification Service)
        // IMPORTANT: Check your Service 2 Port (e.g., 8001)
        // $response = Http::withHeaders([
        //     'Accept' => 'application/json',
        // ])->post('http://127.0.0.1:8080', $data);
        // Log::info('Service 2 Response:', $response->json() ?? ['raw' => $response->body()]);
        return new OrderResource($order->fresh());
    }

    public function delivered(NotificationAuthService $notificationAuthService, Order $order)
    {
        $order->update(['status' => 'delivered']);
        $notificationAuthService->getAccessToken($order); 

        return response()->json(['message' => 'Order completed']);
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
    // public function destroy(Request $request, string $id, DeleteOrderAction $action)
    // {
    //     $order = $request->user()->orders()->findOrFail($id);
    //     $action->handle($order);
    //     return response()->json([
    //         'message' => 'Order deleted successfully.'
    //     ], 200);
    // }

    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $this->authorize('delete', $order);
        $order->delete();
        return response()->json([
            'message' => 'Order deleted successfully.'
        ], 200);
      
    }
}
