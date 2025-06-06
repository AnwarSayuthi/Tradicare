<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: #f8fafc;
    color: #1f2937;
}

.dashboard-wrapper {
    padding: 24px;
    background: #f8fafc;
    min-height: 100vh;
    max-width: 100vw;
    overflow-x: hidden;
}

/* Header Styles */
.dashboard-header {
    margin-bottom: 32px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.welcome-section {
    display: flex;
    align-items: center;
    gap: 24px;
    flex: 1;
}

.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
    white-space: nowrap;
}

.search-container {
    position: relative;
    max-width: 300px;
    flex: 1;
}

.search-input {
    width: 100%;
    padding: 10px 16px 10px 40px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: white;
    font-size: 14px;
    outline: none;
}

.search-input:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 16px;
}

.header-actions {
    display: flex;
    gap: 16px;
    align-items: center;
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border: 1px solid #e5e7eb;
}

.user-profile {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.user-name {
    font-weight: 500;
    color: #374151;
}

.btn {
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    transition: all 0.2s;
}

.btn-primary {
    background: #6366f1;
    color: white;
}

.btn-primary:hover {
    background: #4f46e5;
}

.btn-outline {
    background: white;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-outline:hover {
    background: #f9fafb;
}

/* Metrics Grid */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.metric-card {
    background: white;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.2s;
}

.metric-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.metric-content {
    flex: 1;
}

.metric-title {
    font-size: 14px;
    font-weight: 500;
    color: #6b7280;
    margin-bottom: 8px;
}

.metric-value {
    font-size: 28px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 8px;
}

.metric-change {
    font-size: 12px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 4px;
}

.metric-change.positive {
    color: #10b981;
}

.metric-change.negative {
    color: #ef4444;
}

.metric-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    opacity: 0.8;
}

.metric-card.sales .metric-icon {
    background: #eff6ff;
    color: #2563eb;
}

.metric-card.orders .metric-icon {
    background: #f0fdf4;
    color: #16a34a;
}

.metric-card.appointments .metric-icon {
    background: #fef3c7;
    color: #d97706;
}

.metric-card.revenue .metric-icon {
    background: #f3e8ff;
    color: #9333ea;
}

/* Charts Section */
.charts-section {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 24px;
    margin-bottom: 32px;
}

.chart-container {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid #f1f5f9;
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.chart-header h3 {
    font-size: 18px;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.chart-actions {
    cursor: pointer;
    color: #9ca3af;
    font-size: 18px;
}

.chart-content {
    position: relative;
}

.product-chart .chart-content {
    display: flex;
    align-items: center;
    gap: 24px;
}

.chart-wrapper {
    position: relative;
    width: 200px;
    height: 200px;
}

.chart-center-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.center-value {
    font-size: 24px;
    font-weight: 700;
    color: #1f2937;
}

.chart-legend {
    flex: 1;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
    justify-content: space-between;
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.legend-label {
    flex: 1;
    font-size: 14px;
    color: #6b7280;
}

.sales-chart .chart-content {
    height: 250px;
}

.sales-highlight {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.sales-amount {
    font-size: 24px;
    font-weight: 700;
    color: #1f2937;
}

.sales-change {
    font-size: 14px;
    font-weight: 500;
    padding: 4px 8px;
    border-radius: 6px;
    background: #dcfce7;
    color: #16a34a;
}

.period-label {
    font-size: 14px;
    color: #6b7280;
    background: #f9fafb;
    padding: 4px 12px;
    border-radius: 6px;
}

/* Recent Data Table */
.recent-data-section {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid #f1f5f9;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.table-header h3 {
    font-size: 18px;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.table-actions {
    display: flex;
    gap: 12px;
}

.data-table {
    overflow-x: auto;
}

.data-table table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    text-align: left;
    padding: 12px 16px;
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid #f1f5f9;
}

.data-table td {
    padding: 16px;
    border-bottom: 1px solid #f9fafb;
    font-size: 14px;
}

.customer-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.customer-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #4f46e5;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
}

.customer-name {
    font-weight: 500;
    color: #1f2937;
}

.customer-id {
    font-family: 'Monaco', 'Menlo', monospace;
    color: #6b7280;
    font-size: 13px;
}

.item-info {
    display: flex;
    align-items: center;
    gap: 8px;
}

.item-icon {
    font-size: 16px;
}

.order-date {
    color: #6b7280;
    font-size: 13px;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-transform: capitalize;
}

.status-paid {
    background: #dcfce7;
    color: #16a34a;
}

.status-pending {
    background: #fef3c7;
    color: #d97706;
}

.status-overdue {
    background: #fee2e2;
    color: #dc2626;
}

.price {
    font-weight: 600;
    color: #1f2937;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .charts-section {
        grid-template-columns: 1fr;
    }
    
    .product-chart .chart-content {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
}

@media (max-width: 768px) {
    .dashboard-wrapper {
        padding: 16px;
    }
    
    .header-content {
        flex-direction: column;
        align-items: stretch;
    }
    
    .welcome-section {
        flex-direction: column;
        align-items: stretch;
        gap: 16px;
    }
    
    .metrics-grid {
        grid-template-columns: 1fr;
    }
    
    .page-title {
        font-size: 20px;
    }
    
    .metric-value {
        font-size: 24px;
    }
    
    .data-table {
        font-size: 12px;
    }
    
    .data-table th,
    .data-table td {
        padding: 8px 12px;
    }
}

@media (max-width: 480px) {
    .header-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .user-profile {
        justify-content: center;
    }
    
    .table-header {
        flex-direction: column;
        gap: 16px;
        align-items: stretch;
    }
    
    .table-actions {
        justify-content: center;
    }
}
</style>