<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spareparts', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(\Illuminate\Support\Facades\DB::raw('gen_random_uuid()'));
            $table->uuid('vehicle_id');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->cascadeOnDelete();
            $table->string('name');                       // "Oli Mesin", "Ban Depan"
            $table->bigInteger('price')->default(0);      // harga dalam Rupiah
            $table->date('installed_date')->nullable();
            $table->integer('lifespan')->nullable();      // angka durasi
            $table->string('unit', 10)->default('bulan'); // 'bulan' atau 'hari'
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spareparts');
    }
};
