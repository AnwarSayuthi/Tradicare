<?php

namespace App\Http\Controllers\Process;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServicesController extends Controller
{
    /**
     * Display a listing of the services.
     */
    // Remove this line
    private $categories = ['traditional', 'massage', 'wellness'];
    
    public function index()
    {
        $services = Service::all();
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        return view('admin.services.create');
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
            'category' => 'required|string|max:100',
            'icon' => 'nullable|string|max:50',
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
        $service->category = $request->category;
        $service->icon = $request->icon;
        $service->active = $request->has('active') ? 1 : 0;
        $service->save();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully');
    }

    /**
     * Display the specified service.
     */
    public function show($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'service_name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration_minutes' => 'required|integer|min:15',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'icon' => 'nullable|string|max:50',
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
        $service->category = $request->category;
        $service->icon = $request->icon;
        $service->active = $request->has('active') ? 1 : 0;
        $service->save();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully');
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        
        // Check if service has appointments before deleting
        if ($service->appointments()->count() > 0) {
            return redirect()->route('admin.services.index')
                ->with('error', 'Cannot delete service because it has associated appointments');
        }
        
        $service->delete();
        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully');
    }

    /**
     * Toggle the active status of a service.
     */
    public function toggleStatus($id)
    {
        $service = Service::findOrFail($id);
        $service->active = !$service->active;
        $service->save();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service status updated successfully');
    }
}