@extends('admin_layout')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-wrapper">
    <!-- Header -->
    @include('admin.dashboard.partials.header')
    
    <!-- Metrics Cards -->
    @include('admin.dashboard.partials.metrics')
    
    <!-- Charts Section -->
    @include('admin.dashboard.partials.charts')
    
    <!-- Recent Data Table -->
    @include('admin.dashboard.partials.recent-data')
</div>

@include('admin.dashboard.partials.styles')
@include('admin.dashboard.partials.scripts')
@endsection