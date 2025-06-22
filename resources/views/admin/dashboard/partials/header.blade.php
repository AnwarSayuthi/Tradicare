<div class="dashboard-header">
    <div class="header-content">
        <div class="header-left">
            <div class="welcome-section">
                <h1 class="dashboard-title">Dashboard Overview</h1>
                <p class="dashboard-subtitle">Welcome back! Here's what's happening with your business today.</p>
            </div>
        </div>
        
        <div class="header-right">
            <div class="header-controls">
                <!-- Period Selector -->
                <div class="period-selector">
                    <select id="periodSelect" class="form-select">
                        <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Month</option>
                        <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Year</option>
                    </select>
                </div>
                
                <!-- Month/Year Selector -->
                <div class="date-selector" id="monthSelector" style="{{ $period == 'year' ? 'display: none;' : '' }}">
                    <select id="monthSelect" class="form-select">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                {{ Carbon\Carbon::createFromDate(null, $i, 1)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                
                <div class="date-selector">
                    <select id="yearSelect" class="form-select">
                        @for($i = Carbon\Carbon::now()->year; $i >= Carbon\Carbon::now()->year - 5; $i--)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                
                <!-- Generate Report Button -->
                <button id="generateReport" class="btn btn-primary">
                    <i class="bi bi-file-earmark-text"></i>
                    Generate Report
                </button>
                
                <!-- Refresh Button -->
                <button id="refreshDashboard" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-clockwise"></i>
                    Refresh
                </button>
            </div>
        </div>
    </div>
</div>