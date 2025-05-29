<?php

namespace App\Http\Controllers\Process;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Location;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);
        
        $user = auth()->user();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->save();
        
        return redirect()->route('customer.profile')->with('success', 'Profile updated successfully!');
    }
    
    /**
     * Add a new location for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addLocation(Request $request)
    {
        $request->validate([
            'location_name' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'phone_number' => 'nullable|string|max:20',
        ]);
        
        $user = auth()->user();
        
        // Check if this is the first address (make it default)
        $isDefault = $user->locations()->count() === 0;
        
        $location = new Location($request->all());
        $location->user_id = $user->id;
        $location->is_default = $request->has('is_default') ? true : $isDefault;
        $location->is_pickup_address = $request->has('is_pickup_address');
        $location->is_return_address = $request->has('is_return_address');
        $location->save();
        
        // If this is set as default, remove default from other addresses
        if ($location->is_default) {
            $user->locations()
                ->where('location_id', '!=', $location->location_id)
                ->update(['is_default' => false]);
        }
        
        return redirect()->route('customer.profile')->with('success', 'Address added successfully!');
    }
    
    /**
     * Update an existing location.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateLocation(Request $request, $id)
    {
        $request->validate([
            'location_name' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'phone_number' => 'nullable|string|max:20',
        ]);
        
        $location = Location::where('location_id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        $location->update($request->except(['is_default', 'is_pickup_address', 'is_return_address']));
        
        $location->is_default = $request->has('is_default');
        $location->is_pickup_address = $request->has('is_pickup_address');
        $location->is_return_address = $request->has('is_return_address');
        $location->save();
        
        // If this is set as default, remove default from other addresses
        if ($location->is_default) {
            auth()->user()->locations()
                ->where('location_id', '!=', $location->location_id)
                ->update(['is_default' => false]);
        }
        
        return redirect()->route('customer.profile')->with('success', 'Address updated successfully!');
    }
    
    /**
     * Delete a location.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteLocation($id)
    {
        $location = Location::where('location_id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        $wasDefault = $location->is_default;
        $location->delete();
        
        // If the deleted address was default, set another one as default
        if ($wasDefault) {
            $newDefault = auth()->user()->locations()->first();
            if ($newDefault) {
                $newDefault->is_default = true;
                $newDefault->save();
            }
        }
        
        return redirect()->route('customer.profile')->with('success', 'Address deleted successfully!');
    }
    
    /**
     * Change the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = auth()->user();
        
        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }
        
        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();
        
        return redirect()->route('customer.profile')->with('success', 'Password changed successfully!');
    }
    
    /**
     * Get location details for AJAX request
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getLocation($id)
    {
        $location = Location::where('location_id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        return response()->json($location);
    }
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        // Check if current password matches
        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->with('error', 'Current password is incorrect');
        }

        // Update password
        auth()->user()->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password updated successfully');
    }
    public function index()
    {
        $user = auth()->user();
        $locations = $user->locations;
        $orders = $user->orders()
            ->with(['orderItems.product', 'payment'])
            ->orderBy('order_date', 'desc')
            ->get();
        $appointments = $user->appointments()
            ->with(['service', 'payment'])
            ->orderBy('appointment_date', 'desc')
            ->get();
    
        return view('customer.profile.index', compact('user', 'locations', 'orders', 'appointments'));
    }
}