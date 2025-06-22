<style>
/* Enhanced Dashboard Styles - Following appointments page patterns */
.dashboard-wrapper {
    padding: 1.5rem;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

/* Enhanced Header Styles */
.dashboard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    color: white;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.dashboard-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    background: linear-gradient(45deg, #fff, #e3f2fd);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.dashboard-subtitle {
    font-size: 1rem;
    opacity: 0.9;
    margin: 0.5rem 0 0 0;
}

.header-controls {
    display: flex;
    gap: 0.75rem;
    align-items: center;
    flex-wrap: wrap;
}

.form-select {
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 255, 255, 0.2);
    color: white;
    border-radius: 10px;
    padding: 0.4rem 0.8rem;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.form-select:focus {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.4);
    box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
}

.form-select option {
    background: #492072;
    color: white;
}

.btn {
    border-radius: 10px;
    padding: 0.5rem 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.9rem;
}

.btn-primary {
    background: linear-gradient(135deg, #492072, #6a4c93);
    border-color: #492072;
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(73, 32, 114, 0.3);
    background: linear-gradient(135deg, #6a4c93, #492072);
}

.btn-outline-primary {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.3);
    color: white;
    backdrop-filter: blur(10px);
}

.btn-outline-primary:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 255, 255, 0.2);
}

/* Enhanced Stats Cards - Single Row Layout */
.stats-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}

.stats-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 12px;
    border: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
    overflow: hidden;
    position: relative;
    min-height: 140px;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stats-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
}

.stats-card .card-body {
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    height: 100%;
}

.icon-container {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    font-size: 1.4rem;
    box-shadow: 0 6px 15px rgba(102, 126, 234, 0.3);
    flex-shrink: 0;
}

.stats-content {
    flex: 1;
    min-width: 0;
}

.stats-value {
    font-size: 1.6rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.3rem;
    line-height: 1;
    word-break: break-word;
}

.stats-label {
    font-size: 0.8rem;
    color: #718096;
    font-weight: 600;
    margin-bottom: 0.3rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.stats-change {
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.2rem;
}

.stats-change.positive {
    color: #38a169;
}

.stats-change.negative {
    color: #e53e3e;
}

.stats-breakdown {
    display: flex;
    gap: 0.5rem;
    font-size: 0.75rem;
    color: #718096;
    flex-wrap: wrap;
}

.stats-breakdown span {
    padding: 0.15rem 0.4rem;
    background: #f7fafc;
    border-radius: 4px;
    font-weight: 500;
    white-space: nowrap;
}

/* Enhanced Charts Section - Smaller Charts */
.charts-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.chart-container {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.chart-container:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #f1f3f4;
}

.chart-header h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

.chart-content {
    position: relative;
    height: 220px;
}

.chart-wrapper {
    position: relative;
    height: 100%;
}

.chart-center-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    pointer-events: none;
}

.center-value {
    font-size: 1.4rem;
    font-weight: 700;
    color: #2d3748;
}

.chart-legend {
    margin-top: 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.4rem;
    border-radius: 6px;
    background: #f7fafc;
}

.legend-color {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

.legend-label {
    flex: 1;
    font-size: 0.8rem;
    color: #718096;
}

.sales-highlight {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    flex-wrap: wrap;
}

.sales-amount {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
}

.sales-change {
    padding: 0.25rem 0.6rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
}

.sales-change.positive {
    background: linear-gradient(135deg, #68d391, #38a169);
    color: white;
}

/* Enhanced Recent Data Table */
.recent-data-section {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #f1f3f4;
}

.table-header h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

.table-actions {
    display: flex;
    gap: 0.4rem;
}

.data-table {
    overflow-x: auto;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.data-table table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 10px;
    overflow: hidden;
}

.data-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.data-table thead th {
    padding: 0.75rem 0.6rem;
    text-align: left;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.3px;
    border: none;
}

.data-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f1f3f4;
}

.data-table tbody tr:hover {
    background-color: #f8f9ff;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.data-table tbody td {
    padding: 0.75rem 0.6rem;
    vertical-align: middle;
}

.customer-info {
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

.customer-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.8rem;
    flex-shrink: 0;
}

.customer-name {
    font-weight: 600;
    color: #2d3748;
    font-size: 0.9rem;
}

.item-info {
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.item-icon {
    font-size: 1rem;
}

.status-badge {
    padding: 0.3rem 0.6rem;
    border-radius: 15px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.status-pending {
    background: linear-gradient(135deg, #fdcb6e, #e17055);
    color: white;
}

.status-delivered {
    background: linear-gradient(135deg, #00b894, #00a085);
    color: white;
}

.status-processing {
    background: linear-gradient(135deg, #74b9ff, #0984e3);
    color: white;
}

.status-cancelled {
    background: linear-gradient(135deg, #fd79a8, #e84393);
    color: white;
}

.price {
    font-weight: 700;
    color: #2d3748;
    font-size: 1rem;
}

/* Loading States */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #667eea;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s linear infinite;
}
/* PDF Generation Styles */
.pdf-front-page {
    width: 210mm;
    height: 297mm;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: white;
    font-family: 'Poppins', sans-serif;
    padding: 40px;
    box-sizing: border-box;
}

.pdf-chart-page {
    width: 210mm;
    height: 297mm;
    background: white;
    padding: 20mm;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

@media print {
    .chart-container {
        break-inside: avoid;
        page-break-inside: avoid;
    }
}
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* Responsive Design */
@media (max-width: 1200px) {
    .stats-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .charts-section {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .chart-content {
        height: 200px;
    }
}

@media (max-width: 768px) {
    .dashboard-wrapper {
        padding: 1rem;
    }
    
    .dashboard-header {
        padding: 1rem;
    }
    
    .header-content {
        flex-direction: column;
        align-items: stretch;
        gap: 0.75rem;
    }
    
    .header-controls {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .stats-container {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    
    .stats-card {
        min-height: 120px;
    }
    
    .stats-card .card-body {
        padding: 1rem;
    }
    
    .icon-container {
        width: 45px;
        height: 45px;
        font-size: 1.2rem;
    }
    
    .stats-value {
        font-size: 1.4rem;
    }
    
    .charts-section {
        grid-template-columns: 1fr;
    }
    
    .chart-content {
        height: 180px;
    }
    
    .dashboard-title {
        font-size: 1.75rem;
    }
    
    .chart-container {
        padding: 1rem;
    }
    
    .recent-data-section {
        padding: 1rem;
    }
}

@media (max-width: 480px) {
    .dashboard-wrapper {
        padding: 0.75rem;
    }
    
    .stats-card .card-body {
        flex-direction: column;
        text-align: center;
        padding: 0.75rem;
        gap: 0.75rem;
    }
    
    .stats-content {
        width: 100%;
    }
    
    .icon-container {
        width: 40px;
        height: 40px;
        font-size: 1.1rem;
    }
    
    .stats-value {
        font-size: 1.2rem;
    }
    
    .chart-content {
        height: 160px;
    }
    
    .sales-highlight {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .header-controls {
        flex-direction: column;
        width: 100%;
    }
    
    .header-controls .btn,
    .header-controls .form-select {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 360px) {
    .stats-breakdown {
        flex-direction: column;
        gap: 0.3rem;
    }
    
    .legend-item {
        padding: 0.3rem;
        gap: 0.4rem;
    }
    
    .data-table thead th,
    .data-table tbody td {
        padding: 0.5rem 0.4rem;
        font-size: 0.8rem;
    }
}
</style>