<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management</title>
    
<body>
    <div class="container">
        <h1> Orders Management</h1>

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

        <!-- Analytics Cards -->
        <div class="analytics-grid">
            <div class="analytics-card">
                <div class="analytics-label">Total Orders</div>
                <div class="analytics-value">{{ $analytics['total_orders'] }}</div>
            </div>
            <div class="analytics-card pending">
                <div class="analytics-label">Pending Orders</div>
                <div class="analytics-value">{{ $analytics['pending_orders'] }}</div>
            </div>
            <div class="analytics-card waiting">
                <div class="analytics-label">Waiting Orders</div>
                <div class="analytics-value">{{ $analytics['waiting_orders'] }}</div>
            </div>
            <div class="analytics-card delivered">
                <div class="analytics-label">Delivered Orders</div>
                <div class="analytics-value">{{ $analytics['delivered_orders'] }}</div>
            </div>
            <div class="analytics-card total-price">
                <div class="analytics-label">Total Revenue</div>
                <div class="analytics-value">${{ number_format($analytics['total_price'], 2) }}</div>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('orders.index') }}" class="filters">
            <div class="filter-group">
                <label for="status">Filter by Status:</label>
                <select name="status" id="status" onchange="this.form.submit()">
                    <option value="all" {{ $current_filter == 'all' ? 'selected' : '' }}>All Orders</option>
                    <option value="pending" {{ $current_filter == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="waiting" {{ $current_filter == 'waiting' ? 'selected' : '' }}>Waiting</option>
                    <option value="delivered" {{ $current_filter == 'delivered' ? 'selected' : '' }}>Delivered</option>
                </select>
            </div>

            <label class="checkbox-label">
                <input type="checkbox" name="show_deleted" value="1" 
                       {{ $show_deleted == '1' ? 'checked' : '' }}
                       onchange="this.form.submit()">
                Show Deleted Orders
            </label>
        </form>

        <!-- Orders Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Order Number</th>
                    <th>Status</th>
                    <th>Items Count</th>
                    <th>Total Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td><strong>{{ $order->number }}</strong></td>
                        <td>
                            <span class="status-badge status-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>{{ $order->total_items }} items</td>
                        <td><strong>${{ number_format($order->total_price, 2) }}</strong></td>
                        <td>
                            @if($show_deleted == '1')
                                <!-- Deleted order actions -->
                                <form action="{{ route('orders.restore', $order->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="action-btn btn-restore">Restore</button>
                                </form>
                                <form action="{{ route('orders.force-destroy', $order->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete" 
                                            onclick="return confirm('Permanently delete this order? This cannot be undone!')">
                                        Delete Forever
                                    </button>
                                </form>
                            @else
                                <!-- Active order actions -->
                                <a href="{{ route('orders.show', $order->id) }}" class="action-btn btn-view">View</a>
                                <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete" 
                                            onclick="return confirm('Delete this order? You can restore it later.')">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p>No orders found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>
</body>
</html>