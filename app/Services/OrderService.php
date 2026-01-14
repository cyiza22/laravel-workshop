<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderService
{
    public function fetchAll(Request $request, int $userId)
    {
        $status = $request->query('status');
        $showDeleted = $request->query('show_deleted', '0');

        $ordersQuery = Order::where('user_id', $userId)
            ->with('orderItems')
            ->withCount('orderItems');

        if ($status && $status !== 'all') {
            $ordersQuery->where('status', $status);
        }

        if ($showDeleted === '1') {
            $ordersQuery->onlyTrashed();
        }

        $orders = $ordersQuery->paginate(10);

        $totalPrice = Order::where('user_id', $userId)
            ->with('orderItems')
            ->get()
            ->sum(fn ($order) =>
                $order->orderItems->sum(
                    fn ($item) => $item->unit_price * $item->quantity
                )
            );

        $analytics = [
            'total_orders'     => Order::where('user_id', $userId)->count(),
            'pending_orders'   => Order::where('user_id', $userId)->where('status', 'pending')->count(),
            'waiting_orders'   => Order::where('user_id', $userId)->where('status', 'waiting')->count(),
            'delivered_orders' => Order::where('user_id', $userId)->where('status', 'delivered')->count(),
            'total_price'      => $totalPrice,
        ];
        return [
            'orders' => $orders,
            'analytics' => $analytics,
            'status' => $status,
            'show_deleted' => $showDeleted,
        ];
    
    }

    public function fetchAllApi(Request $request, int $userId)
{
    return Order::where('user_id', $userId)
        ->with('orderItems')
        ->withCount('orderItems')
        ->paginate(10);
}

    public function create ($data)
    {
        return Order::create($data);
    }

    public function updateStatus(Order $order, array $data)
    {
        $status = $data['status'] ?? null;
        if (!$status) {
            throw new \InvalidArgumentException('Status is required to update order.');
        }

        return Order::where('id', $order->id)->update($data);
    }

    public function delete($order)
    {
        return $order->delete();
    }


      
}
