<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Since SQLite doesn't support dropping constraints easily, 
     * we need to recreate the table with the proper constraints.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            // Create a new table with the correct schema and constraints
            DB::statement("CREATE TABLE writer_profiles_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id BIGINT UNSIGNED NOT NULL,
                education_level VARCHAR(255) NOT NULL,
                bio TEXT NULL,
                expertise_areas JSON NULL,
                hourly_rate DECIMAL(8, 2) NULL,
                rating FLOAT DEFAULT 0 NOT NULL,
                availability_status VARCHAR(255) DEFAULT 'available' NOT NULL CHECK (availability_status IN ('available', 'busy')),
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
            )");

            // Copy data from the old table to the new one
            DB::statement("INSERT INTO writer_profiles_new SELECT * FROM writer_profiles");

            // Drop the old table and rename the new one
            Schema::drop('writer_profiles');
            Schema::rename('writer_profiles_new', 'writer_profiles');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need for down migration as we're just fixing constraints
    }
};
