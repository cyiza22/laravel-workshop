<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order {{ $order->number }}</title>
    
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order: {{ $order->number }}</h1>
            <span class="status-badge status-{{ $order->status }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="error-message">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Order Summary -->
        <div class="order-summary">
            <div class="summary-card">
                <div class="summary-label">Total Items</div>
                <div class="summary-value">{{ $order->total_items ?? 0 }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Total Price</div>
                <div class="summary-value">${{ number_format($order->total_price ?? 0, 2) }}</div>
            </div>
        </div>

        <!-- Status Update Section -->
        <div class="status-update-section">
            <h3>Update Order Status</h3>
            @if($order->status !== 'delivered')
                <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="status-form">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="status">Change Status:</label>
                        <select name="status" id="status" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="waiting" {{ $order->status == 'waiting' ? 'selected' : '' }}>Waiting</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary">Update Status</button>
                </form>
            @else
                <div class="disabled-notice">
                    This order has been delivered and cannot be updated.
                </div>
            @endif
        </div>

        <!-- Order Items -->
        <h3>Order Items</h3>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->orderItems ?? [] as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>${{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td><strong>${{ number_format($item->unit_price * $item->quantity, 2) }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; ">
                            No items in this order yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Add Item Form -->
        <div class="form-container">
            <h3>Add New Item to Order</h3>
            <form action="{{ route('order_items.store') }}" method="POST">
                @csrf
                
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="product_id">Select Product:</label>
                    <select name="product_id" id="product_id" required>
                        <option value="">-- Select a Product --</option>
                        @if(isset($products) && is_iterable($products))
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} - ${{ number_format($product->price, 2) }}
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>No products available</option>
                        @endif
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity', 1) }}" required>
                </div>

                <button type="submit" class="btn-primary">Add Item to Order</button>
            </form>
        </div>

        <a href="{{ route('orders.index') }}" class="back-link">← Back to Orders List</a>
    </div>
</body>
</html>