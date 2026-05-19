<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom ke tabel users default Laravel
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
            $table->text('avatar_url')->nullable()->after('phone');
        });

        Schema::create('vehicles', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(\Illuminate\Support\Facades\DB::raw('gen_random_uuid()'));
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('brand', 100)->nullable();
            $table->string('type', 50)->default('Motor Matic');
            $table->smallInteger('year')->nullable();
            $table->string('plate', 20)->nullable();
            $table->integer('odometer')->default(0);
            $table->string('owner', 100)->nullable();
            $table->text('photo_url')->nullable();
            $table->date('next_service_date')->nullable();
            $table->integer('next_service_km')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'avatar_url']);
        });
    }
};
