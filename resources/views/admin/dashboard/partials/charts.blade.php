<div class="charts-section">
    <div class="chart-container product-chart">
        <div class="chart-header">
            <h3>Product Categories</h3>
            <div class="chart-actions">
                <i class="bi bi-three-dots"></i>
            </div>
        </div>
        <div class="chart-content">
            <div class="chart-wrapper">
                <canvas id="productChart"></canvas>
                <div class="chart-center-text">
                    <div class="center-value">{{ $metrics['totalProducts'] }}</div>
                    <div class="center-label">Total Products</div>
                </div>
            </div>
            <div class="chart-legend">
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