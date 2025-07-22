<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->string('regis');
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('first_visit')->nullable();
            $table->string('interest')->nullable();
            $table->string('offer')->nullable();
            $table->enum('status_fu', ['normal', 'warm', 'hot'])->default('normal');
            $table->date('tanggal_closing')->nullable();
            $table->date('sheet_month')->nullable();
            $table->text('report')->nullable();
            $table->string('alasan_depo_decline')->nullable();
            $table->integer('fu_jumlah')->default(0);
            $table->date('fu_ke_1')->nullable();
            $table->boolean('fu_checkbox')->default(false);
            $table->date('next_fu')->nullable();
            $table->json('fu_dates')->nullable(); // Store FU ke 2, 3, dst
            $table->text('notes')->nullable(); // Manual notes by agent
            $table->date('followup_date')->nullable(); // Manual follow-up date
            $table->timestamps();
            
            // Unique constraint untuk nama + user_id (agent)
            $table->unique(['nama', 'user_id']);
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
