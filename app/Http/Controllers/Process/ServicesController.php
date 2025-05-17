<?php

namespace App\Http\Controllers\Process;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ServicesController extends Controller
{
    public function index()
    {
        // Get service statistics
        $totalServices = Service::count();
        $activeServices = Service::where('active', 1)->count();
        $inactiveServices = Service::where('active', 0)->count();
        
        // Change from all() to paginate()
        $services = Service::paginate(10); // You can adjust the number 10 to how many items per page you want
        return view('admin.services.index', compact('services', 'totalServices', 'activeServices', 'inactiveServices'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        // Get service statistics
        $totalServices = Service::count();
        $activeServices = Service::where('active', 1)->count();
        $inactiveServices = Service::where('active', 0)->count();
        
        return view('admin.services.create', compact('totalServices', 'activeServices', 'inactiveServices'));
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration_minutes' => 'required|integer|min:15',
            'price' => 'required|numeric|min:0',
            'active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $service = new Service();
        $service->service_name = $request->service_name;
        $service->description = $request->description;
        $service->duration_minutes = $request->duration_minutes;
        $service->price = $request->price;
        $service->active = $request->has('active') ? 1 : 0;
        $service->save();

        return redirect()->route('admin.services.show', $service->service_id)
            ->with('success', 'Service created successfully');
    }

    /**
     * Display the specified service.
     */
    public function show($id)
    {
        // Get service statistics
        $totalServices = Service::count();
        $activeServices = Service::where('active', 1)->count();
        $inactiveServices = Service::where('active', 0)->count();
        
        $service = Service::findOrFail($id);
        return view('admin.services.show', compact('service', 'totalServices', 'activeServices', 'inactiveServices'));
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit($id)
    {
        // Get service statistics
        $totalServices = Service::count();
        $activeServices = Service::where('active', 1)->count();
        $inactiveServices = Service::where('active', 0)->count();
        
        $service = Service::findOrFail($id);
        return view('admin.services.edit', compact('service', 'totalServices', 'activeServices', 'inactiveServices'));
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'service_name' => 'required|string|max:255',
                'description' => 'required|string',
                'duration_minutes' => 'required|integer|min:15',
                'price' => 'required|numeric|min:0',
                'active' => 'boolean'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $service = Service::findOrFail($id);
            $service->service_name = $request->service_name;
            $service->description = $request->description;
            $service->duration_minutes = $request->duration_minutes;
            $service->price = $request->price;
            $service->active = $request->has('active') ? 1 : 0;
            
            if (!$service->save()) {
                throw new \Exception('Failed to update service');
            }

            return redirect()->route('admin.services.show', $id)
                ->with('success', 'Service updated successfully');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update service: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        
        // Delete the service and all associated appointments
        // First delete associated appointments
        $service->appointments()->delete();
        
        // Then delete the service
        $service->delete();
        
        return redirect()->route('admin.services.index')
            ->with('success', 'Service and its associated appointments deleted successfully');
    }

    /**
     * Toggle the active status of a service.
     */
    public function toggleStatus($id)
    {
        $service = Service::findOrFail($id);
        $service->active = !$service->active;
        $service->save();

        return redirect()->route('admin.services.show', $service->service_id)
            ->with('success', 'Service status updated successfully');
    }
}