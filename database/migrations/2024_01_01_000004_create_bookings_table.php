<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('staff_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', [
                'pending', 'confirmed', 'picked_up', 'cleaning',
                'completed', 'delivered', 'cancelled', 'rescheduled'
            ])->default('pending');
            $table->enum('service_type', ['wash', 'dry', 'fold', 'iron', 'wash_dry_fold', 'wash_iron', 'full_service']);
            $table->decimal('total_weight', 8, 2)->nullable();
            $table->decimal('total_price', 10, 2);
            $table->text('notes')->nullable();
            $table->string('pickup_address');
            $table->string('delivery_address');
            $table->dateTime('pickup_date');
            $table->dateTime('delivery_date');
            $table->dateTime('pickup_scheduled_at')->nullable();
            $table->dateTime('delivery_scheduled_at')->nullable();
            $table->dateTime('picked_up_at')->nullable();
            $table->dateTime('cleaning_started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->string('payment_method')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('booking_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_services');
        Schema::dropIfExists('bookings');
    }
};
