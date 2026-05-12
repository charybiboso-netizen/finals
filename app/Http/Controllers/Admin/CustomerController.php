<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::byRole('customer')->latest()->paginate(10);
        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        $bookings = Booking::where('customer_id', $customer->id)
            ->with('services')
            ->latest()
            ->paginate(10);

        return view('admin.customers.show', compact('customer', 'bookings'));
    }

    public function toggleStatus(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        $newStatus = $customer->status === 'active' ? 'inactive' : 'active';
        $customer->update(['status' => $newStatus]);

        ActivityLog::log(auth()->id(), 'customer_status_toggled', "Changed customer {$customer->name} status to {$newStatus}");

        return redirect()->route('admin.customers.index')->with('success', "Customer account {$newStatus} successfully.");
    }

    public function destroy(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        ActivityLog::log(auth()->id(), 'customer_deleted', "Deleted customer account: {$customer->name}");
        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Customer account deleted successfully.');
    }
}
