@extends('admin.nav')

@section('title', 'Appointment Schedule')

@section('content')
<div class="container mt-4">
    <x-card>
        <x-slot name="header">
            <h2 class="mb-0">Appointment Schedule</h2>
        </x-slot>

        <div class="mb-3">
            <x-buttons.primary>This Month</x-buttons.primary>
            <x-buttons.secondary>Week</x-buttons.secondary>
        </div>

        <div class="row">
            @foreach ($appointments as $appointment)
                <div class="col-md-4 mb-3">
                    <x-card>
                        <h5>{{ $appointment['title'] }}</h5>
                        <p>Time: {{ $appointment['time'] }}</p>
                        <p>Date: {{ $appointment['date'] }}</p>
                        <x-buttons.primary href="{{ $appointment['link'] }}" target="_blank">
                            Details
                        </x-buttons.primary>
                    </x-card>
                </div>
            @endforeach
        </div>
    </x-card>
</div>
@endsection
