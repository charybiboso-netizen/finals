<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\RatingController as AdminRatingController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\BookingController as StaffBookingController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Customer\ServiceController as CustomerServiceController;
use App\Http\Controllers\Customer\RatingController as CustomerRatingController;
use App\Http\Controllers\Customer\NotificationController as CustomerNotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        $user = auth()->user();
        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'staff' => redirect()->route('staff.dashboard'),
            default => redirect()->route('customer.dashboard'),
        };
    })->name('dashboard');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('staff', StaffController::class)->except(['show']);
        Route::get('/staff/pending', [StaffController::class, 'pending'])->name('staff.pending');
        Route::post('/staff/{staff}/approve', [StaffController::class, 'approve'])->name('staff.approve');
        Route::post('/staff/{staff}/reject', [StaffController::class, 'reject'])->name('staff.reject');
        Route::post('/staff/{staff}/toggle-status', [StaffController::class, 'toggleStatus'])->name('staff.toggle-status');

        Route::resource('customers', AdminCustomerController::class)->only(['index', 'show', 'destroy']);
        Route::post('/customers/{customer}/toggle-status', [AdminCustomerController::class, 'toggleStatus'])->name('customers.toggle-status');

        Route::resource('bookings', AdminBookingController::class)->only(['index', 'show', 'edit', 'update']);
        Route::post('/bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');
        Route::post('/bookings/{booking}/mark-paid', [AdminBookingController::class, 'markAsPaid'])->name('bookings.mark-paid');
        Route::post('/bookings/{booking}/mark-unpaid', [AdminBookingController::class, 'markAsUnpaid'])->name('bookings.mark-unpaid');
        Route::post('/bookings/{booking}/assign-staff', [AdminBookingController::class, 'assignStaff'])->name('bookings.assign-staff');

        Route::resource('services', ServiceController::class)->except(['show']);
        Route::post('/services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
        Route::get('/reports/weekly', [ReportController::class, 'weekly'])->name('reports.weekly');
        Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
        Route::get('/reports/transactions', [ReportController::class, 'transactions'])->name('reports.transactions');

        Route::resource('notifications', AdminNotificationController::class)->except(['edit', 'update']);
        Route::resource('activity-logs', ActivityLogController::class)->only(['index', 'show']);
        Route::resource('ratings', AdminRatingController::class)->only(['index', 'show', 'destroy']);
        Route::post('/ratings/{rating}/approve', [AdminRatingController::class, 'approve'])->name('ratings.approve');
    });

    Route::middleware('staff')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
        Route::get('/bookings', [StaffBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [StaffBookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{booking}/update-status', [StaffBookingController::class, 'updateStatus'])->name('bookings.update-status');
        Route::get('/pickup-schedule', [StaffBookingController::class, 'pickupSchedule'])->name('pickup-schedule');
        Route::get('/delivery-schedule', [StaffBookingController::class, 'deliverySchedule'])->name('delivery-schedule');
    });

    Route::middleware('customer')->prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/services', [CustomerServiceController::class, 'index'])->name('services.index');
        Route::get('/services/{service}', [CustomerServiceController::class, 'show'])->name('services.show');

        Route::get('/bookings/create', [CustomerBookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [CustomerBookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings', [CustomerBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [CustomerBookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{booking}/cancel', [CustomerBookingController::class, 'cancel'])->name('bookings.cancel');
        Route::get('/bookings/{booking}/track', [CustomerBookingController::class, 'track'])->name('bookings.track');
        Route::get('/booking-history', [CustomerBookingController::class, 'history'])->name('bookings.history');

        Route::get('/bookings/{booking}/rate', [CustomerRatingController::class, 'create'])->name('ratings.create');
        Route::post('/bookings/{booking}/rate', [CustomerRatingController::class, 'store'])->name('ratings.store');

        Route::get('/notifications', [CustomerNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{notification}/read', [CustomerNotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/mark-all-read', [CustomerNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
