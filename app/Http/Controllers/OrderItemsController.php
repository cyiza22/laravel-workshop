<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $order = Order::findOrFail($request->input('order_id'));
        $order->orderItems()->create([
            'product_id' => $request->input('product_id'),
            'product_name' => $request->input('product_name'),
            'unit_price' => $request->input('unit_price'),
            'quantity' => $request->input('quantity')
        ]);
        return redirect()->route('orders.show', ['id' => $order->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
