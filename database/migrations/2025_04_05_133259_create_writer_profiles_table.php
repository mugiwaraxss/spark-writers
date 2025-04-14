<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('writer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('education_level');
            $table->text('bio')->nullable();
            $table->json('expertise_areas')->nullable();
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->float('rating')->default(0);
            $table->string('availability_status')->default('available');
            $table->timestamps();
        });
        
        // Add check constraint for SQLite compatibility
        DB::statement("ALTER TABLE writer_profiles ADD CONSTRAINT chk_availability_status CHECK (availability_status IN ('available', 'busy'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('writer_profiles');
    }
};
