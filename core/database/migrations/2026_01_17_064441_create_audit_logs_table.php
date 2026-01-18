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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->comment('Stores audit trail for important system actions');

            $table->id();
            $table->foreignId('actor_user_id')->comment('The user who performed the action')->constrained('users');
            $table->foreignId('teacher_id')->nullable()->comment('The tenant context for the action')->constrained('teachers');
            $table->string('action')->comment('Machine-readable action code (e.g., AUTH_LOGIN)');
            $table->string('target_type')->nullable()->comment('Polymorphic relation: target model class');
            $table->unsignedBigInteger('target_id')->nullable()->comment('Polymorphic relation: target model ID');
            $table->json('metadata')->nullable()->comment('Additional context for the action');
            $table->timestamps();

            $table->index(['teacher_id', 'action']);
            $table->index(['target_type', 'target_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
