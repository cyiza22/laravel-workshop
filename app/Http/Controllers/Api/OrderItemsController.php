<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\OrderItemResource;
use App\Models\Order;

class OrderItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $orderId)
    {
        $order = $request->user()->orders()->findOrFail($orderId);
        return OrderItemResource::collection($order->orderItems()->paginate(10));
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
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
