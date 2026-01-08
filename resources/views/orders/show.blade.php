<h2>Order {{ $order->number }}</h2>

<h3>Items</h3>
<table>
    <tr>
        <th>Product</th>
        <th>Unit Price</th>
        <th>Quantity</th>
    </tr>

    @foreach($order->items as $item)
        <tr>
            <td>{{ $item->product_name }}</td>
            <td>{{ $item->unit_price }}</td>
            <td>{{ $item->quantity }}</td>
        </tr>
    @endforeach
</table>
