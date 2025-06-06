<div class="recent-data-section">
    <div class="table-header">
        <h3>Recent Invoice</h3>
        <div class="table-actions">
            <button class="btn btn-outline">Filter</button>
            <button class="btn btn-outline">View</button>
        </div>
    </div>
    
    <div class="data-table">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Customer</th>
                    <th>Customer name</th>
                    <th>Items Name</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentData['orders'] as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="customer-id">#{{ str_pad($order['id'], 6, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <div class="customer-info">
                            <div class="customer-avatar">{{ substr($order['customer'], 0, 1) }}</div>
                            <span class="customer-name">{{ $order['customer'] }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="item-info">
                            <div class="item-icon">📦</div>
                            <span>Order Items</span>
                        </div>
                    </td>
                    <td class="order-date">{{ $order['date'] }}</td>
                    <td>
                        <span class="status-badge status-{{ strtolower($order['status']) }}">
                            {{ ucfirst($order['status']) }}
                        </span>
                    </td>
                    <td class="price">RM{{ number_format($order['amount'], 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No recent orders found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>