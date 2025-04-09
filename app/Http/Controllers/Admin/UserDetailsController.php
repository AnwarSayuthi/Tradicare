<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class UserDetailsController extends Controller
{
    /**
     * Display the list of user details.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Example data for users (Replace with actual database query later)
        $users = [
            [
                'id' => 1,
                'name' => 'Customer 1',
                'email' => 'customer1@example.com',
                'contact' => '+60123456789',
                'status' => 'active',
                'created_at' => '2025-01-01',
            ],
            [
                'id' => 2,
                'name' => 'Customer 2',
                'email' => 'customer2@example.com',
                'contact' => '+60123456789',
                'status' => 'inactive',
                'created_at' => '2025-01-02',
            ],
            [
                'id' => 3,
                'name' => 'Customer 3',
                'email' => 'customer3@example.com',
                'contact' => '+60123456789',
                'status' => 'active',
                'created_at' => '2025-01-03',
            ],
            [
                'id' => 4,
                'name' => 'Customer 4',
                'email' => 'customer4@example.com',
                'contact' => '+60123456789',
                'status' => 'inactive',
                'created_at' => '2025-01-04',
            ],
        ];

        // Pass data to the view
        return view('admin.userDetails', compact('users'));
    }
}
