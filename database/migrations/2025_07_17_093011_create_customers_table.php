<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('sheet_name');
            $table->integer('row_number');
            $table->date('tanggal')->nullable();
            $table->string('regis')->nullable();
            $table->string('nama')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('first_visit')->nullable();
            $table->string('interest')->nullable();
            $table->string('offer')->nullable();
            $table->enum('status_fu', ['normal', 'warm', 'hot'])->default('normal');
            $table->date('tanggal_closing')->nullable();
            $table->text('report')->nullable();
            $table->text('alasan_depo_decline')->nullable();
            $table->text('fu')->nullable();
            $table->timestamps();
            
            $table->index(['sheet_name', 'row_number']);
            $table->index('status_fu');
            $table->index('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
