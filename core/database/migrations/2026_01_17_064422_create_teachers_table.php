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
        Schema::create('teachers', function (Blueprint $table) {
            $table->comment('Stores teacher tenant information');

            $table->id();
            $table->foreignId('user_id')->comment('Link to the users table')->constrained('users')->cascadeOnDelete();
            $table->string('display_name')->comment('Public display name for the teacher');
            $table->string('status', 32)->default('active')->comment('Teacher tenant status: ACTIVE, SUSPENDED');
            $table->timestamps();

            $table->unique('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
