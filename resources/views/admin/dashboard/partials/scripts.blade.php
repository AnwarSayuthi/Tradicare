<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart data from controller
const salesData = {!! json_encode($charts['sales']) !!};
const productData = {!! json_encode($charts['products']) !!};

// Product Chart (Doughnut)
const productCtx = document.getElementById('productChart').getContext('2d');
const productChart = new Chart(productCtx, {
    type: 'doughnut',
    data: {
        labels: productData.map(item => item.label),
        datasets: [{
            data: productData.map(item => item.value),
            backgroundColor: ['#4F46E5', '#10B981', '#F59E0B'],
            borderWidth: 0,
            cutout: '75%'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: 'white',
                bodyColor: 'white',
                borderColor: 'rgba(255, 255, 255, 0.1)',
                borderWidth: 1,
                cornerRadius: 8
            }
        }
    }
});

// Sales Chart (Line)
const salesCtx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: salesData.map(item => item.label),
        datasets: [{
            label: 'Income',
            data: salesData.map(item => item.value),
            borderColor: '#4F46E5',
            backgroundColor: 'rgba(79, 70, 229, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#4F46E5',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 3,
            pointRadius: 6,
            pointHoverRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: 'white',
                bodyColor: 'white',
                borderColor: 'rgba(255, 255, 255, 0.1)',
                borderWidth: 1,
                cornerRadius: 8,
                displayColors: false
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                },
                border: {
                    display: false
                },
                ticks: {
                    color: '#9CA3AF',
                    font: {
                        size: 12
                    }
                }
            },
            y: {
                grid: {
                    color: 'rgba(156, 163, 175, 0.1)',
                    borderDash: [5, 5]
                },
                border: {
                    display: false
                },
                ticks: {
                    color: '#9CA3AF',
                    font: {
                        size: 12
                    },
                    callback: function(value) {
                        return 'RM' + value.toLocaleString();
                    }
                }
            }
        },
        interaction: {
            intersect: false,
            mode: 'index'
        }
    }
});

// Period selector functionality
document.getElementById('periodSelect')?.addEventListener('change', function() {
    const period = this.value;
    window.location.href = `{{ route('admin.dashboard') }}?period=${period}`;
});

// Search functionality
document.querySelector('.search-input')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        // Implement search functionality
        console.log('Search:', this.value);
    }
});

// Responsive chart resize
window.addEventListener('resize', function() {
    productChart.resize();
    salesChart.resize();
});
</script>
<script>
// Auto-refresh data every 30 seconds
setInterval(function() {
    // You can implement AJAX refresh here if needed
}, 30000);
</script>