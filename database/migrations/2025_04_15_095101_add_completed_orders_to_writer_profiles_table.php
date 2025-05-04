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
        Schema::table('writer_profiles', function (Blueprint $table) {
            $table->integer('completed_orders')->default(0)->after('rating');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('writer_profiles', function (Blueprint $table) {
            $table->dropColumn('completed_orders');
        });

        Schema::dropIfExists('password_reset_tokens');
    }
};
