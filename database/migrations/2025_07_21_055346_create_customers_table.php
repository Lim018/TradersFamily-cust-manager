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
            $table->enum('status_fu', ['normal', 'warm', 'hot', 'normal(prospect)', 'warm(potential)', 'hot(closeable)']);
            $table->string('tanggal_closing')->nullable();
            $table->string('report')->nullable();
            $table->string('alasan_depo_decline')->nullable();
            $table->integer('fu_jumlah')->default(0);
            $table->string('fu_ke_1')->nullable();
            $table->boolean('fu_checkbox_1')->default(false);
            $table->string('fu_ke_2')->nullable();
            $table->boolean('fu_checkbox_2')->default(false);
            $table->string('fu_ke_3')->nullable();
            $table->boolean('fu_checkbox_3')->default(false);
            $table->string('fu_ke_4')->nullable();
            $table->boolean('fu_checkbox_4')->default(false);
            $table->string('fu_ke_5')->nullable();
            $table->boolean('fu_checkbox_5')->default(false);
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
