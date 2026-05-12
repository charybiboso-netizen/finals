<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::byRole('staff')->latest()->paginate(10);
        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'staff',
            'status' => 'active',
        ]);

        ActivityLog::log(auth()->id(), 'staff_created', "Created staff account: {$request->name}");

        return redirect()->route('admin.staff.index')->with('success', 'Staff account created successfully.');
    }

    public function edit(User $staff)
    {
        if (!$staff->isStaff()) {
            abort(404);
        }
        return view('admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        if (!$staff->isStaff()) {
            abort(404);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',id,'.$staff->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $staff->update($request->only(['name', 'email', 'phone', 'address']));

        ActivityLog::log(auth()->id(), 'staff_updated', "Updated staff account: {$staff->name}");

        return redirect()->route('admin.staff.index')->with('success', 'Staff account updated successfully.');
    }

    public function destroy(User $staff)
    {
        if (!$staff->isStaff()) {
            abort(404);
        }

        ActivityLog::log(auth()->id(), 'staff_deleted', "Deleted staff account: {$staff->name}");
        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff account deleted successfully.');
    }

    public function pending()
    {
        $staff = User::byRole('staff')->pending()->latest()->paginate(10);
        return view('admin.staff.pending', compact('staff'));
    }

    public function approve(User $staff)
    {
        if (!$staff->isStaff()) {
            abort(404);
        }

        $staff->update(['status' => 'active']);

        ActivityLog::log(auth()->id(), 'staff_approved', "Approved staff account: {$staff->name}");

        return redirect()->route('admin.staff.pending')->with('success', 'Staff account approved successfully.');
    }

    public function reject(User $staff)
    {
        if (!$staff->isStaff()) {
            abort(404);
        }

        $staff->update(['status' => 'inactive']);

        ActivityLog::log(auth()->id(), 'staff_rejected', "Rejected staff account: {$staff->name}");

        return redirect()->route('admin.staff.pending')->with('success', 'Staff account rejected.');
    }

    public function toggleStatus(User $staff)
    {
        if (!$staff->isStaff()) {
            abort(404);
        }

        $newStatus = $staff->status === 'active' ? 'inactive' : 'active';
        $staff->update(['status' => $newStatus]);

        ActivityLog::log(auth()->id(), 'staff_status_toggled', "Changed staff {$staff->name} status to {$newStatus}");

        return redirect()->route('admin.staff.index')->with('success', "Staff account {$newStatus} successfully.");
    }
}
