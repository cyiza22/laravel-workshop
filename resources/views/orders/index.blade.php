<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
</head>
<body>
    <h1>Orders</h1>

        {{-- ================= Analytics ================= --}}
        <div style="margin-bottom:20px; padding:10px; border:1px solid #ccc;">
            <h3>Shop Analytics</h3>
            <p>Total Orders: {{ $analytics['total_orders'] }}</p>
            <p>Pending Orders: {{ $analytics['pending_orders'] }}</p>
            <p>Waiting Orders: {{ $analytics['waiting_orders'] }}</p>
            <p>Delivered Orders: {{ $analytics['delivered_orders'] }}</p>
            <p><strong>Total Revenue:</strong> {{ $analytics['total_revenue'] }}</p>
        </div>

        {{-- ================= Filter ================= --}}
        <form method="GET" action="/orders" style="margin-bottom:20px;">
            <label>Filter by status:</label>
            <select name="status">
                <option value="">All</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="waiting" {{ $status === 'waiting' ? 'selected' : '' }}>Waiting</option>
                <option value="delivered" {{ $status === 'delivered' ? 'selected' : '' }}>Delivered</option>
            </select>
            <button type="submit">Filter</button>
        </form>

        {{-- ================= Orders Table ================= --}}
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Number</th>
                    <th>Status</th>
                    <th>Items Count</th>
                    <th>Total Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->number }}</td>
                        <td>{{ $order->status }}</td>
                        <td>{{ $order->items_count }}</td>
                        <td>{{ $order->total_price }}</td>
                        <td>
                            <a href="/orders/{{ $order->id }}">View</a>

                            {{-- Delete (soft delete) --}}
                            <form method="POST" action="/orders/{{ $order->id }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <br>

        {{ $orders->links() }}

</body>
</html>