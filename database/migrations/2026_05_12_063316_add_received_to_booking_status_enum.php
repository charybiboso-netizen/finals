<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM(
            'pending', 'confirmed', 'picked_up', 'cleaning',
            'completed', 'delivered', 'received', 'cancelled', 'rescheduled'
        ) NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("UPDATE bookings SET status = 'delivered' WHERE status = 'received'");
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM(
            'pending', 'confirmed', 'picked_up', 'cleaning',
            'completed', 'delivered', 'cancelled', 'rescheduled'
        ) NOT NULL DEFAULT 'pending'");
    }
};
