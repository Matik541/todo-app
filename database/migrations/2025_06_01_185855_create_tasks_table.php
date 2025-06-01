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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->string('name', 255); 
            $table->text('description')->nullable(); 
            $table->enum('priority', ['low', 'medium', 'high'])->default('low'); 
            $table->enum('status', ['to-do', 'in progress', 'done'])->default('to-do'); 
            $table->date('due_date'); 
            $table->string('access_token')->nullable()->unique(); 
            $table->timestamp('token_expires_at')->nullable(); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};