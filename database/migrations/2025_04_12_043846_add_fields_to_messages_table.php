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
        Schema::table('messages', function (Blueprint $table) {
            $table->string('sender_type')->default('client')->after('sender_id'); // client, writer, admin
            $table->string('recipient_type')->nullable()->after('recipient_id'); // client, writer, admin
            $table->string('attachment_path')->nullable()->after('content');
            $table->string('attachment_name')->nullable()->after('attachment_path');
            $table->boolean('has_revision_request')->default(false)->after('read_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('sender_type');
            $table->dropColumn('recipient_type');
            $table->dropColumn('attachment_path');
            $table->dropColumn('attachment_name');
            $table->dropColumn('has_revision_request');
        });
    }
};
