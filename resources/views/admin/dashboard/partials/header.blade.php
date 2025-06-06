<div class="dashboard-header">
    <div class="header-content">
        <div class="welcome-section">
            <h1 class="page-title">Welcome Back, {{ Auth::user()->name }} 👋</h1>
            <div class="search-container">
                <input type="text" placeholder="Search anything" class="search-input">
                <i class="bi bi-search search-icon"></i>
            </div>
        </div>
        <div class="header-actions">
            <div class="notification-icon">
                <i class="bi bi-bell"></i>
            </div>
            <div class="user-profile">
                <span class="user-name">{{ Auth::user()->name }}</span>
            </div>
        </div>
    </div>
</div>
<div class="header-content">
    <div class="period-selector">
        <select id="periodSelect" class="form-select">
            <option value="month" {{ $period == 'month' ? 'selected' : '' }}>This Month</option>
            <option value="year" {{ $period == 'year' ? 'selected' : '' }}>This Year</option>
        </select>
    </div>
    <button class="btn btn-primary report-btn">
        <i class="bi bi-file-earmark-text"></i> Report
    </button>
</div>