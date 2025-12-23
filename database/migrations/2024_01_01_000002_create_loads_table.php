<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loads', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->text('pickup_address');
            $table->text('delivery_address');
            $table->dateTime('pickup_at')->nullable();
            $table->dateTime('delivery_at')->nullable();
            $table->string('status')->default('pending');
            $table->foreignId('assigned_driver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('assigned_driver_id');
            $table->index('pickup_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loads');
    }
};
