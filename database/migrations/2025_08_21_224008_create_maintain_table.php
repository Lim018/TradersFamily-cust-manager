<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintain', function (Blueprint $table) {
            $table->id();
            $table->string('agent_code', 50);
            $table->date('tanggal')->nullable();
            $table->string('regis', 100)->nullable();
            $table->string('alasan_depo', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->decimal('deposit', 15, 2)->default(0);
            $table->decimal('wd', 15, 2)->default(0);
            $table->decimal('nmi', 15, 2)->default(0);
            $table->decimal('lot', 15, 2)->default(0);
            $table->decimal('profit', 15, 2)->default(0);
            $table->decimal('last_balance', 15, 2)->default(0);
            $table->string('status_data', 50)->nullable();
            $table->string('upsell', 100)->nullable();

            // FU1 - FU5 dipisah
            for ($i = 1; $i <= 5; $i++) {
                $table->date("fu_{$i}_date")->nullable();
                $table->boolean("fu_{$i}_checked")->default(false);
                $table->string("fu_{$i}_note", 255)->nullable();
            }

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintain');
    }
};
