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
            $table->string('tanggal');
            $table->string('regis');
            $table->string('nama')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('first_visit')->nullable();
            $table->string('interest')->nullable();
            $table->string('offer')->nullable();
            $table->enum('status_fu', ['normal', 'warm', 'hot', 'Normal (Prospect)', 'Warm (Potential)', 'Hot (Closeable)']);
            $table->string('tanggal_closing')->nullable();
            $table->string('report')->nullable();
            $table->string('alasan_depo_decline')->nullable();
            $table->integer('fu_jumlah')->default(0);
            $table->string('fu_ke_1')->nullable();
            $table->boolean('fu_checkbox')->default(false);
            $table->string('next_fu')->nullable();
            $table->json('fu_dates')->nullable(); // Store FU ke 2, 3, dst
            $table->string('sheet_month')->nullable();
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
