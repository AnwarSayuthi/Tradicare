<div class="metrics-section">
    <div class="stats-container">
        <!-- Total Sales Card -->
        <div class="stats-card sales-card">
            <div class="card-body">
                <div class="icon-container">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-value">RM{{ number_format($metrics['totalSales'], 2) }}</div>
                    <div class="stats-label">Total Sales</div>
                    <div class="stats-change {{ $metrics['salesGrowth'] >= 0 ? 'positive' : 'negative' }}">
                        <i class="bi bi-{{ $metrics['salesGrowth'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                        {{ abs($metrics['salesGrowth']) }}% from last {{ $period }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Orders Card -->
        <div class="stats-card orders-card">
            <div class="card-body">
                <div class="icon-container">
                    <i class="bi bi-cart-check"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ number_format($metrics['totalOrders']) }}</div>
                    <div class="stats-label">Total Orders</div>
                    <div class="stats-breakdown">
                        <span class="completed">{{ $metrics['completedOrders'] }} completed</span>
                        <span class="pending">{{ $metrics['pendingOrders'] }} pending</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Appointments Card -->
        <div class="stats-card appointments-card">
            <div class="card-body">
                <div class="icon-container">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ number_format($metrics['totalAppointments']) }}</div>
                    <div class="stats-label">Total Appointments</div>
                    <div class="stats-breakdown">
                        <span class="rate">{{ $metrics['appointmentRate'] }}% completion rate</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Revenue Card -->
        <div class="stats-card revenue-card">
            <div class="card-body">
                <div class="icon-container">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-value">RM{{ number_format($metrics['totalRevenue'], 2) }}</div>
                    <div class="stats-label">Total Revenue</div>
                    <div class="stats-breakdown">
                        <span class="customers">{{ $metrics['totalCustomers'] }} customers</span>
                        <span class="new">{{ $metrics['newCustomers'] }} new</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>