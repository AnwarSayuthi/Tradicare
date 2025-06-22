@extends('admin_layout')

@section('title', 'Dashboard - Tradicare Admin')

@section('content')
<div class="dashboard-wrapper">
    <!-- Enhanced Header -->
    @include('admin.dashboard.partials.header')
    
    <!-- Enhanced Metrics Cards -->
    @include('admin.dashboard.partials.metrics')
    
    <!-- Enhanced Charts Section -->
    @include('admin.dashboard.partials.charts')
    
    <!-- Enhanced Recent Data Table -->
    @include('admin.dashboard.partials.recent-data')
</div>

@include('admin.dashboard.partials.styles')
@include('admin.dashboard.partials.scripts')
@endsection