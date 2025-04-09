@extends('admin.nav')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h5>Sales</h5>
            <canvas id="salesChart"></canvas>
        </div>
        <div class="col-md-6">
            <h5>Statistics</h5>
            <canvas id="statisticsChart"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Example of chart rendering using Chart.js
    var ctxSales = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctxSales, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Monthly Sales',
                data: [12, 19, 3, 5, 2, 3, 7, 8, 12, 15, 10, 20],
                borderColor: 'rgba(75, 192, 192, 1)',
                fill: false
            }]
        }
    });

    var ctxStatistics = document.getElementById('statisticsChart').getContext('2d');
    var statisticsChart = new Chart(ctxStatistics, {
        type: 'bar',
        data: {
            labels: ['Segment A', 'Segment B', 'Segment C'],
            datasets: [{
                label: 'Statistics',
                data: [10, 20, 30],
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
            }]
        }
    });
</script>
@endpush
