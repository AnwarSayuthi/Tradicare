<div class="charts-section">
    <div class="chart-container product-chart">
        <div class="chart-header">
            <h3>Product chart</h3>
            <div class="chart-actions">
                <i class="bi bi-three-dots"></i>
            </div>
        </div>
        <div class="chart-content">
            <div class="chart-wrapper">
                <canvas id="productChart"></canvas>
                <div class="chart-center-text">
                    <div class="center-value">{{ number_format($metrics['totalRevenue']) }}</div>
                </div>
            </div>
            <div class="chart-legend">
                <div class="legend-item">
                    <span class="legend-color" style="background: #4F46E5;"></span>
                    <span class="legend-label">Total Paid</span>
                    <strong>{{ $metrics['completedOrders'] }}</strong>
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background: #10B981;"></span>
                    <span class="legend-label">Total Overdue</span>
                    <strong>{{ $metrics['pendingOrders'] }}</strong>
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background: #F59E0B;"></span>
                    <span class="legend-label">Total Draft</span>
                    <strong>{{ $metrics['totalProducts'] }}</strong>
                </div>
            </div>
        </div>
    </div>
    
    <div class="chart-container sales-chart">
        <div class="chart-header">
            <h3>Sales Analytics</h3>
            <div class="chart-period">
                <span class="period-label">month</span>
            </div>
        </div>
        <div class="chart-content">
            <div class="sales-highlight">
                <span class="sales-amount">RM{{ number_format($metrics['totalSales']) }}</span>
                <span class="sales-change positive">+12%</span>
            </div>
            <canvas id="salesChart"></canvas>
        </div>
    </div>
</div>