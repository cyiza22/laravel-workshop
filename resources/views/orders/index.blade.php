<h2>Orders</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Number</th>
        <th>Action</th>
    </tr>

    @foreach($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->number }}</td>
            <td>
                <a href="/orders/{{ $order->id }}">View</a>
                <form action="/orders/{{ $order->id }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
            </td>
        </tr>
    @endforeach
</table>

{{ $orders->links() }}
