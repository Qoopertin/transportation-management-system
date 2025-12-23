<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_breadcrumbs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('load_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->timestamp('captured_at');
            $table->timestamps();

            $table->index('load_id');
            $table->index(['user_id', 'captured_at']);
            $table->index('captured_at'); // for pruning old data
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_breadcrumbs');
    }
};
