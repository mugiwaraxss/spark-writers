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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('writer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('academic_level');
            $table->string('subject_area');
            $table->integer('word_count');
            $table->dateTime('deadline');
            $table->enum('status', ['pending', 'assigned', 'in_progress', 'completed', 'revision', 'disputed'])->default('pending');
            $table->decimal('price', 10, 2);
            $table->string('citation_style')->nullable();
            $table->integer('sources_required')->nullable();
            $table->json('services')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
