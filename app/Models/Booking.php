<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_code', 'customer_id', 'staff_id', 'status', 'service_type',
        'total_weight', 'total_price', 'notes', 'pickup_address', 'delivery_address',
        'pickup_date', 'delivery_date', 'pickup_scheduled_at', 'delivery_scheduled_at',
        'picked_up_at', 'cleaning_started_at', 'completed_at', 'delivered_at',
        'received_at', 'cancelled_at', 'cancellation_reason', 'is_paid', 'payment_method', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'pickup_date' => 'datetime',
            'delivery_date' => 'datetime',
            'pickup_scheduled_at' => 'datetime',
            'delivery_scheduled_at' => 'datetime',
            'picked_up_at' => 'datetime',
            'cleaning_started_at' => 'datetime',
            'completed_at' => 'datetime',
            'delivered_at' => 'datetime',
            'received_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'paid_at' => 'datetime',
            'is_paid' => 'boolean',
            'total_weight' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'booking_services')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function staffAssignments()
    {
        return $this->hasMany(StaffAssignment::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function updateStatus(string $status): bool
    {
        $timestamps = [
            'picked_up' => 'picked_up_at',
            'cleaning' => 'cleaning_started_at',
            'completed' => 'completed_at',
            'delivered' => 'delivered_at',
            'received' => 'received_at',
            'cancelled' => 'cancelled_at',
        ];

        $data = ['status' => $status];
        if (isset($timestamps[$status])) {
            $data[$timestamps[$status]] = now();
        }

        return $this->update($data);
    }
}
