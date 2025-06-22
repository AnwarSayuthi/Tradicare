{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
<script>
// Check if Chart.js is loaded
if (typeof Chart === 'undefined') {
    console.error('Chart.js is not loaded!');
    // Show user-friendly error message
    document.querySelectorAll('.chart-content').forEach(container => {
        container.innerHTML = '<div style="text-align: center; padding: 20px; color: #ef4444;">Charts failed to load. Please refresh the page.</div>';
    });
} else {
    console.log('Chart.js loaded successfully');
    
    // Register Chart.js plugins with better error checking
    try {
        const pluginsToRegister = [];
        
        if (typeof ChartDataLabels !== 'undefined') {
            pluginsToRegister.push(ChartDataLabels);
        }
        
        if (window['chartjs-plugin-annotation']) {
            pluginsToRegister.push(window['chartjs-plugin-annotation']);
        }
        
        if (window['chartjs-adapter-date-fns']) {
            pluginsToRegister.push(window['chartjs-adapter-date-fns']);
        }
        
        if (pluginsToRegister.length > 0) {
            Chart.register(...pluginsToRegister);
            console.log('Chart.js plugins registered successfully:', pluginsToRegister.length);
        }
    } catch (error) {
        console.error('Error registering Chart.js plugins:', error);
    }
}

// Chart data from controller
const salesData = {!! json_encode($charts['sales']) !!};
const ordersData = {!! json_encode($charts['orders']) !!};
const appointmentData = {!! json_encode($charts['appointments']) !!};
const productData = {!! json_encode($charts['products']) !!}; // ADD THIS LINE

// Enhanced Product Chart (Doughnut) with Advanced Features
const productCtx = document.getElementById('productChart')?.getContext('2d');
if (productCtx) {
    // Define category colors with gradients
    const categoryColorMap = {
        'Supplements': {
            background: '#4F46E5',
            gradient: ['#4F46E5', '#6366F1']
        },
        'Oil & Balms': {
            background: '#10B981',
            gradient: ['#10B981', '#34D399']
        },
        'Beverages': {
            background: '#F59E0B',
            gradient: ['#F59E0B', '#FBBF24']
        },
        'Body Care': {
            background: '#EF4444',
            gradient: ['#EF4444', '#F87171']
        },
        'Immune Booster': {
            background: '#8B5CF6',
            gradient: ['#8B5CF6', '#A78BFA']
        }
    };
    
    // Create gradient backgrounds
    const createGradient = (ctx, color1, color2) => {
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, color1);
        gradient.addColorStop(1, color2);
        return gradient;
    };
    
    // Generate enhanced colors and patterns
    const chartColors = productData.map(item => {
        const colorConfig = categoryColorMap[item.label];
        if (colorConfig) {
            return createGradient(productCtx, colorConfig.gradient[0], colorConfig.gradient[1]);
        }
        return '#6B7280';
    });
    
    const chartLabels = productData.map(item => item.label);
    
    const productChart = new Chart(productCtx, {
        type: 'doughnut',
        data: {
            labels: chartLabels,
            datasets: [{
                data: productData.map(item => item.value),
                backgroundColor: chartColors,
                borderWidth: 3,
                borderColor: '#ffffff',
                cutout: '70%',
                hoverBorderWidth: 5,
                hoverBorderColor: '#ffffff',
                hoverBackgroundColor: chartColors.map(color => color + 'CC')
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            aspectRatio: 1, // Add this to maintain square aspect
            layout: {
                padding: {
                    top: 10,
                    bottom: 10,
                    left: 10,
                    right: 10
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.9)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: 'rgba(255, 255, 255, 0.2)',
                    borderWidth: 2,
                    cornerRadius: 12,
                    displayColors: true,
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} products (${percentage}%)`;
                        },
                        afterLabel: function(context) {
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            return `Share: ${((value / total) * 100).toFixed(1)}% of total inventory`;
                        }
                    }
                },
                datalabels: {
                    display: function(context) {
                        const value = context.parsed;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        return (value / total) > 0.05; // Only show labels for segments > 5%
                    },
                    color: '#ffffff',
                    font: {
                        weight: 'bold',
                        size: 11
                    },
                    formatter: function(value, context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(0);
                        return percentage + '%';
                    },
                    anchor: 'center',
                    align: 'center'
                }
            },
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 2000,
                easing: 'easeInOutQuart'
            },
            elements: {
                arc: {
                    borderJoinStyle: 'round'
                }
            }
        },
        plugins: [ChartDataLabels]
    });
    
    // Add click interaction
    productChart.canvas.onclick = function(evt) {
        const points = productChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
        if (points.length) {
            const firstPoint = points[0];
            const label = productChart.data.labels[firstPoint.index];
            const value = productChart.data.datasets[firstPoint.datasetIndex].data[firstPoint.index];
            console.log(`Clicked on ${label}: ${value} products`);
            // Add your click handler logic here
        }
    };
}

// Enhanced Sales Chart (Line) with Advanced Features
const salesCtx = document.getElementById('salesChart')?.getContext('2d');
if (salesCtx) {
    // Create gradient for sales chart
    const salesGradient = salesCtx.createLinearGradient(0, 0, 0, 400);
    salesGradient.addColorStop(0, 'rgba(102, 126, 234, 0.8)');
    salesGradient.addColorStop(1, 'rgba(102, 126, 234, 0.1)');
    
    const salesBorderGradient = salesCtx.createLinearGradient(0, 0, 0, 400);
    salesBorderGradient.addColorStop(0, '#667eea');
    salesBorderGradient.addColorStop(1, '#764ba2');
    
    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: salesData.map(item => item.label),
            datasets: [{
                label: 'Sales Revenue',
                data: salesData.map(item => item.value),
                borderColor: salesBorderGradient,
                backgroundColor: salesGradient,
                borderWidth: 4,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 3,
                pointRadius: 8,
                pointHoverRadius: 12,
                pointHoverBackgroundColor: '#764ba2',
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 4,
                segment: {
                    borderColor: ctx => {
                        // Fix: Add null checks for ctx properties
                        if (!ctx || !ctx.p1 || !ctx.p0 || !ctx.p1.parsed || !ctx.p0.parsed) {
                            return '#667eea'; // Default color
                        }
                        const current = ctx.p1.parsed.y;
                        const previous = ctx.p0.parsed.y;
                        return current > previous ? '#10B981' : '#EF4444';
                    }
                }
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            aspectRatio: 2, // Add this for wider aspect ratio
            layout: {
                padding: {
                    top: 20,
                    bottom: 20,
                    left: 10,
                    right: 10
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.9)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: 'rgba(255, 255, 255, 0.2)',
                    borderWidth: 2,
                    cornerRadius: 12,
                    displayColors: false,
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        title: function(context) {
                            return `Period: ${context[0].label}`;
                        },
                        label: function(context) {
                            return `Revenue: RM${context.parsed.y.toLocaleString()}`;
                        },
                        afterLabel: function(context) {
                            const dataIndex = context.dataIndex;
                            if (dataIndex > 0) {
                                const current = context.parsed.y;
                                const previous = salesData[dataIndex - 1].value;
                                const change = ((current - previous) / previous * 100).toFixed(1);
                                const trend = change >= 0 ? '📈' : '📉';
                                return `${trend} ${Math.abs(change)}% from previous period`;
                            }
                            return '';
                        }
                    }
                },
                annotation: {
                    annotations: {
                        averageLine: {
                            type: 'line',
                            yMin: salesData.reduce((sum, item) => sum + item.value, 0) / salesData.length,
                            yMax: salesData.reduce((sum, item) => sum + item.value, 0) / salesData.length,
                            borderColor: '#F59E0B',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            label: {
                                content: 'Average',
                                enabled: true,
                                position: 'end',
                                backgroundColor: '#F59E0B',
                                color: '#ffffff',
                                font: {
                                    size: 11,
                                    weight: 'bold'
                                }
                            }
                        }
                    }
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
                            size: 12,
                            weight: '500'
                        },
                        padding: 10
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
                            size: 12,
                            weight: '500'
                        },
                        padding: 10,
                        callback: function(value) {
                            return 'RM' + value.toLocaleString();
                        }
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart',
                onProgress: function(animation) {
                    const progress = animation.currentStep / animation.numSteps;
                    // Fix: Add null check for chart and ctx
                    if (this.chart && this.chart.ctx) {
                        this.chart.ctx.globalAlpha = progress;
                    }
                },
                onComplete: function() {
                    // Fix: Add null check for chart and ctx
                    if (this.chart && this.chart.ctx) {
                        this.chart.ctx.globalAlpha = 1;
                    }
                }
            },
            elements: {
                point: {
                    hoverRadius: 12
                },
                line: {
                    borderJoinStyle: 'round',
                    borderCapStyle: 'round'
                }
            }
        }
    });
}

// Dashboard Controls
document.addEventListener('DOMContentLoaded', function() {
    const periodSelect = document.getElementById('periodSelect');
    const monthSelector = document.getElementById('monthSelector');
    const monthSelect = document.getElementById('monthSelect');
    const yearSelect = document.getElementById('yearSelect');
    const generateReportBtn = document.getElementById('generateReport');
    const refreshBtn = document.getElementById('refreshDashboard');
    
    // Period change handler
    if (periodSelect) {
        periodSelect.addEventListener('change', function() {
            if (this.value === 'year') {
                monthSelector.style.display = 'none';
            } else {
                monthSelector.style.display = 'block';
            }
            updateDashboard();
        });
    }
    
    // Month/Year change handlers
    if (monthSelect) {
        monthSelect.addEventListener('change', updateDashboard);
    }
    
    if (yearSelect) {
        yearSelect.addEventListener('change', updateDashboard);
    }
    
    // Generate report handler
    if (generateReportBtn) {
        generateReportBtn.addEventListener('click', function() {
            generateReport();
        });
    }
    
    // Refresh dashboard handler
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            location.reload();
        });
    }
    
    function updateDashboard() {
        const period = periodSelect?.value || 'month';
        const month = monthSelect?.value || new Date().getMonth() + 1;
        const year = yearSelect?.value || new Date().getFullYear();
        
        const url = new URL(window.location);
        url.searchParams.set('period', period);
        url.searchParams.set('month', month);
        url.searchParams.set('year', year);
        
        window.location.href = url.toString();
    }
    
    function generateReport() {
        const period = periodSelect?.value || 'month';
        const month = monthSelect?.value || new Date().getMonth() + 1;
        const year = yearSelect?.value || new Date().getFullYear();
        
        // Show loading state
        generateReportBtn.disabled = true;
        generateReportBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Generating PDF...';
        
        // Generate PDF using html2canvas
        generatePDFReport(period, month, year);
    }
    
    async function generatePDFReport(period, month, year) {
        try {
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF('p', 'mm', 'a4');
            const pageWidth = 210; // A4 width in mm
            const pageHeight = 297; // A4 height in mm
            
            // Create front page
            await createFrontPage(pdf, period, month, year, pageWidth, pageHeight);
            
            // Add chart pages
            await addChartPages(pdf, pageWidth, pageHeight);
            
            // Save the PDF
            const fileName = `Tradicare-Dashboard-Report-${period}-${year}${period === 'month' ? '-' + month : ''}.pdf`;
            pdf.save(fileName);
            
            showNotification('PDF report generated successfully!', 'success');
        } catch (error) {
            console.error('Error generating PDF:', error);
            showNotification('Failed to generate PDF report', 'error');
        } finally {
            // Reset button state
            generateReportBtn.disabled = false;
            generateReportBtn.innerHTML = '<i class="bi bi-file-earmark-text"></i> Generate Report';
        }
    }
    
    async function createFrontPage(pdf, period, month, year, pageWidth, pageHeight) {
        // Create a temporary front page element
        const frontPageElement = document.createElement('div');
        frontPageElement.style.cssText = `
            width: 210mm;
            height: 297mm;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            font-family: 'Poppins', sans-serif;
            position: absolute;
            top: -9999px;
            left: -9999px;
            padding: 40px;
            box-sizing: border-box;
        `;
        
        // Get current date
        const currentDate = new Date().toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        // Get period text
        const periodText = period === 'month' ? 
            `${new Date(year, month - 1).toLocaleDateString('en-US', { month: 'long', year: 'numeric' })}` : 
            `Year ${year}`;
        
        frontPageElement.innerHTML = `
            <div style="text-align: center;">
                <div style="width: 120px; height: 120px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; backdrop-filter: blur(10px); border: 3px solid rgba(255,255,255,0.3);">
                    <img src="{{ asset('image/logo.png') }}" alt="Tradicare Logo" style="width: 80px; height: 80px; object-fit: contain;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div style="display: none; font-size: 36px; font-weight: bold; color: white;">TC</div>
                </div>
                <h1 style="font-size: 48px; font-weight: 700; margin: 20px 0; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); background: linear-gradient(45deg, #fff, #e3f2fd); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">TRADICARE</h1>
                <h2 style="font-size: 32px; font-weight: 600; margin: 15px 0; opacity: 0.9;">Dashboard Report</h2>
                <div style="background: rgba(255,255,255,0.15); padding: 20px 30px; border-radius: 15px; margin: 30px 0; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                    <h3 style="font-size: 24px; font-weight: 600; margin: 0 0 10px 0;">Report Period</h3>
                    <p style="font-size: 20px; margin: 0; opacity: 0.9;">${periodText}</p>
                </div>
                <div style="background: rgba(255,255,255,0.15); padding: 20px 30px; border-radius: 15px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                    <h3 style="font-size: 20px; font-weight: 600; margin: 0 0 10px 0;">Generated On</h3>
                    <p style="font-size: 18px; margin: 0; opacity: 0.9;">${currentDate}</p>
                </div>
            </div>
        `;
        
        document.body.appendChild(frontPageElement);
        
        // Convert to canvas and add to PDF
        const canvas = await html2canvas(frontPageElement, {
            width: 794, // A4 width in pixels at 96 DPI
            height: 1123, // A4 height in pixels at 96 DPI
            scale: 2,
            useCORS: true,
            allowTaint: true,
            backgroundColor: null
        });
        
        const imgData = canvas.toDataURL('image/png');
        pdf.addImage(imgData, 'PNG', 0, 0, pageWidth, pageHeight);
        
        // Clean up
        document.body.removeChild(frontPageElement);
    }
    
    async function addChartPages(pdf, pageWidth, pageHeight) {
        const charts = [
            { 
                element: '.chart-container.product-chart', 
                title: 'Product Chart',
                canvasId: 'productChart'
            },
            { 
                element: '.chart-container.sales-chart', 
                title: 'Sales Analytics',
                canvasId: 'salesChart'
            }
        ];
        
        for (let i = 0; i < charts.length; i++) {
            const chartInfo = charts[i];
            const chartElement = document.querySelector(chartInfo.element);
            
            if (chartElement) {
                // Add new page
                pdf.addPage();
                
                // Wait for charts to be fully rendered
                await new Promise(resolve => setTimeout(resolve, 500));
                
                // Create a wrapper for the chart with title
                const chartWrapper = document.createElement('div');
                chartWrapper.style.cssText = `
                    width: 210mm;
                    height: 297mm;
                    background: white;
                    padding: 20mm;
                    box-sizing: border-box;
                    position: absolute;
                    top: -9999px;
                    left: -9999px;
                    font-family: 'Poppins', sans-serif;
                    display: flex;
                    flex-direction: column;
                `;
                
                // Create title section
                const titleSection = document.createElement('div');
                titleSection.style.cssText = `
                    text-align: center;
                    margin-bottom: 30px;
                    padding-bottom: 20px;
                    border-bottom: 3px solid #667eea;
                    flex-shrink: 0;
                `;
                titleSection.innerHTML = `
                    <h1 style="font-size: 32px; font-weight: 700; color: #667eea; margin: 0;">${chartInfo.title}</h1>
                    <p style="font-size: 16px; color: #666; margin: 10px 0 0 0;">Tradicare Dashboard Report</p>
                `;
                
                // Clone the chart element with proper styling
                const clonedChart = chartElement.cloneNode(true);
                clonedChart.style.cssText = `
                    width: 100%;
                    height: 500px;
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
                    border-radius: 12px;
                    padding: 1.5rem;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                    border: 1px solid rgba(0, 0, 0, 0.05);
                `;
                
                // Ensure chart content is properly sized
                const chartContent = clonedChart.querySelector('.chart-content');
                if (chartContent) {
                    chartContent.style.cssText = `
                        position: relative;
                        height: 400px;
                        flex: 1;
                    `;
                }
                
                // Make sure canvas is visible and properly sized
                // Around line 602 - rename to canvasElement
                const canvasElement = clonedChart.querySelector('canvas');
                if (canvasElement) {
                    canvasElement.style.cssText = `
                        width: 100% !important;
                        height: 100% !important;
                        max-width: 100%;
                        max-height: 100%;
                    `;
                } else {
                    console.warn(`Canvas element not found for chart: ${chartInfo.title}`);
                }

                chartWrapper.appendChild(titleSection);
                chartWrapper.appendChild(clonedChart);
                
                document.body.appendChild(chartWrapper);
                
                // Convert to canvas with higher quality settings
                const chartCanvas = await html2canvas(chartWrapper, {
                    width: 794,
                    height: 1123,
                    scale: 2,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#ffffff',
                    logging: false,
                    removeContainer: true,
                    foreignObjectRendering: true,
                    imageTimeout: 15000,
                    onclone: function(clonedDoc) {
                        // Ensure all styles are applied in the cloned document
                        const clonedCanvas = clonedDoc.querySelector('canvas');
                        if (clonedCanvas) {
                            clonedCanvas.style.display = 'block';
                            clonedCanvas.style.width = '100%';
                            clonedCanvas.style.height = '100%';
                        }
                    }
                });
                
                const imgData = chartCanvas.toDataURL('image/png', 1.0);
                pdf.addImage(imgData, 'PNG', 0, 0, pageWidth, pageHeight);
                
                // Clean up
                document.body.removeChild(chartWrapper);
            } else {
                console.warn(`Chart element not found: ${chartInfo.element}`);
            }
        }
    }
    
    function downloadReport(data) {
        const reportContent = `
            TRADICARE DASHBOARD REPORT
            Period: ${data.period}
            Generated: ${data.generated_at}
            
            SALES METRICS:
            - Total Sales: RM${data.metrics.totalSales.toLocaleString()}
            - Total Orders: ${data.metrics.totalOrders}
            - Total Revenue: RM${data.metrics.totalRevenue.toLocaleString()}
            - Sales Growth: ${data.metrics.salesGrowth}%
            
            BUSINESS METRICS:
            - Total Appointments: ${data.metrics.totalAppointments}
            - Appointment Rate: ${data.metrics.appointmentRate}%
            - Total Customers: ${data.metrics.totalCustomers}
            - New Customers: ${data.metrics.newCustomers}
            
            ORDER STATUS:
            - Completed Orders: ${data.metrics.completedOrders}
            - Pending Orders: ${data.metrics.pendingOrders}
            
            PRODUCT METRICS:
            - Total Products: ${data.metrics.totalProducts}
            - Low Stock Products: ${data.metrics.lowStockProducts}
        `;
        
        const blob = new Blob([reportContent], { type: 'text/plain' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `dashboard-report-${data.period.replace(' ', '-').toLowerCase()}.txt`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }
    
    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        // Add styles
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            z-index: 9999;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            background: ${type === 'success' ? 'linear-gradient(135deg, #00b894, #00a085)' : 'linear-gradient(135deg, #fd79a8, #e84393)'};
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        `;
        
        document.body.appendChild(notification);
        
        // Show notification
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Hide notification after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
});

// Auto-refresh dashboard every 5 minutes
setInterval(() => {
    if (document.visibilityState === 'visible') {
        location.reload();
    }
}, 300000);
</script>
<script>
// Auto-refresh data every 30 seconds
setInterval(function() {
    // You can implement AJAX refresh here if needed
}, 30000);
</script>