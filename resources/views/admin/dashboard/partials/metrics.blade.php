<div class="metrics-grid">
    <div class="metric-card sales">
        <div class="metric-content">
            <h3 class="metric-title">Total Sales</h3>
            <div class="metric-value">{{ number_format($metrics['totalSales']) }}</div>
            <div class="metric-change positive">
                <i class="bi bi-arrow-up"></i> +4.5% from last month
            </div>
        </div>
        <div class="metric-icon">
            <i class="bi bi-graph-up"></i>
        </div>
    </div>
    
    <div class="metric-card orders">
        <div class="metric-content">
            <h3 class="metric-title">Total Order</h3>
            <div class="metric-value">RM {{ number_format($metrics['totalOrders']) }}</div>
            <div class="metric-change negative">
                <i class="bi bi-arrow-down"></i> -2.1% from last month
            </div>
        </div>
        <div class="metric-icon">
            <i class="bi bi-bag-check"></i>
        </div>
    </div>
    
    <div class="metric-card appointments">
        <div class="metric-content">
            <h3 class="metric-title">Total appointment</h3>
            <div class="metric-value">{{ $metrics['appointmentRate'] }}%</div>
            <div class="metric-change negative">
                <i class="bi bi-arrow-down"></i> -0.5% from last month
            </div>
        </div>
        <div class="metric-icon">
            <i class="bi bi-calendar-check"></i>
        </div>
    </div>
    
    <div class="metric-card revenue">
        <div class="metric-content">
            <h3 class="metric-title">Total Invoice</h3>
            <div class="metric-value">{{ number_format($metrics['totalRevenue']) }}</div>
            <div class="metric-change positive">
                <i class="bi bi-arrow-up"></i> +1.2% from last month
            </div>
        </div>
        <div class="metric-icon">
            <i class="bi bi-currency-dollar"></i>
        </div>
    </div>
</div>