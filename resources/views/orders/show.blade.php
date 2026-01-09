<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order {{ $order->number }}</title>
</head>
<body>
    <h1>Order {{ $order->number }}</h1>

        <p><strong>Status:</strong> {{ $order->status }}</p>

        {{-- ================= Status Update ================= --}}
        @if($order->status !== 'delivered')
            <form method="POST" action="/orders/{{ $order->id }}/status">
                @csrf
                @method('PATCH')

                <label>Change Status:</label>
                <select name="status">
                    <option value="pending">Pending</option>
                    <option value="waiting">Waiting</option>
                    <option value="delivered">Delivered</option>
                </select>

                <button type="submit">Update Status</button>
            </form>
        @else
            <p>This order has been delivered and cannot be modified.</p>
        @endif

        <hr>

        {{-- ================= Items ================= --}}
        <h3>Order Items</h3>

        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->unit_price }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->unit_price * $item->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <br>

        <a href="/orders">← Back to orders</a>

</body>
</html>