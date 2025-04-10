<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function profile()
    {
        $user = auth()->user();
        return view('customer.profile', compact('user'));
    }
    
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'tel_number' => 'nullable|string'
        ]);
        
        $user->update($validated);
        
        return redirect()->route('customer.profile')
                        ->with('success', 'Profile updated successfully');
    }
    
    public function changePassword()
    {
        return view('customer.change-password');
    }
    
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed'
        ]);
        
        $user = auth()->user();
        
        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect']);
        }
        
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        
        return redirect()->route('customer.profile')
                        ->with('success', 'Password changed successfully');
    }
}
