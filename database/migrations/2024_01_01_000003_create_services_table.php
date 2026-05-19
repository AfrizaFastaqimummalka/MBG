<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(\Illuminate\Support\Facades\DB::raw('gen_random_uuid()'));
            $table->uuid('vehicle_id');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->cascadeOnDelete();
            $table->date('date');
            $table->bigInteger('cost')->default(0);       // Rupiah
            $table->integer('odometer')->default(0);
            $table->string('type')->nullable();           // "Ganti Oli + Filter"
            $table->string('workshop')->nullable();       // nama bengkel
            $table->text('notes')->nullable();
            $table->date('next_date')->nullable();        // jadwal berikutnya
            $table->integer('next_km')->nullable();       // target KM berikutnya
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
