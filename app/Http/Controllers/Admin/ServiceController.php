<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::latest()->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'in:per_piece,per_kg,per_load'],
            'type' => ['required', 'in:wash,dry,fold,iron,wash_dry_fold,wash_iron,full_service'],
            'estimated_hours' => ['required', 'integer', 'min:1'],
        ]);

        Service::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'unit' => $request->unit,
            'type' => $request->type,
            'estimated_hours' => $request->estimated_hours,
        ]);

        ActivityLog::log(auth()->id(), 'service_created', "Created service: {$request->name}");

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'in:per_piece,per_kg,per_load'],
            'type' => ['required', 'in:wash,dry,fold,iron,wash_dry_fold,wash_iron,full_service'],
            'estimated_hours' => ['required', 'integer', 'min:1'],
        ]);

        $service->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'unit' => $request->unit,
            'type' => $request->type,
            'estimated_hours' => $request->estimated_hours,
        ]);

        ActivityLog::log(auth()->id(), 'service_updated', "Updated service: {$service->name}");

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function toggleStatus(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);

        ActivityLog::log(auth()->id(), 'service_status_toggled', "Toggled service {$service->name} status");

        return redirect()->route('admin.services.index')->with('success', 'Service status updated successfully.');
    }

    public function destroy(Service $service)
    {
        ActivityLog::log(auth()->id(), 'service_deleted', "Deleted service: {$service->name}");
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }
}
