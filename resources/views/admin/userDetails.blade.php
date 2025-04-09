@extends('admin.nav')

@section('title', 'User Details')

@section('content')
<div class="container mt-4">
    <h2>User Details</h2>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button class="btn btn-primary">Export</button>
            <button class="btn btn-secondary">Import</button>
        </div>
        <button class="btn btn-success">Download</button>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact No.</th>
                <th>Status</th>
                <th>Date Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td><img src="placeholder.png" alt="User Image" width="50"></td>
                    <td>{{ $user['name'] }}</td>
                    <td>{{ $user['email'] }}</td>
                    <td>{{ $user['contact'] }}</td>
                    <td>
                        <button class="btn btn-sm {{ $user['status'] == 'active' ? 'btn-success' : 'btn-warning' }}">
                            {{ ucfirst($user['status']) }}
                        </button>
                    </td>
                    <td>{{ $user['created_at'] }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-primary">Update</a>
                        <a href="#" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination mt-3">
        <button class="btn btn-light">1</button>
        <button class="btn btn-light">2</button>
        <button class="btn btn-light">...</button>
        <button class="btn btn-light">10</button>
    </div>
</div>
@endsection
