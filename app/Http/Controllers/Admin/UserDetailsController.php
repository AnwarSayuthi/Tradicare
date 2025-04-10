<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserDetailsController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'customer')->get();
        return view('admin.userDetails', compact('users'));
    }

    public function show($id)
    {
        $user = User::with(['orders', 'appointments', 'payments'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required|in:admin,customer',
            'tel_number' => 'nullable|string'
        ]);

        $user->update($validated);
        return redirect()->route('admin.userDetails')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.userDetails')->with('success', 'User deleted successfully');
    }
}
