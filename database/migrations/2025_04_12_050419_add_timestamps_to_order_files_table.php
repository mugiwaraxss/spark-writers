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
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            // Check the table structure
            $structure = DB::select("PRAGMA table_info(order_files)");
            $columnNames = collect($structure)->pluck('name')->toArray();
            
            // Check if we're missing the updated_at column
            if (!in_array('updated_at', $columnNames)) {
                // Create a new table with the same structure plus updated_at
                $columns = collect($structure)->map(function($column) {
                    $type = $column->type;
                    $null = $column->notnull ? ' NOT NULL' : ' NULL';
                    $default = $column->dflt_value ? ' DEFAULT ' . $column->dflt_value : '';
                    $pk = $column->pk ? ' PRIMARY KEY' : '';
                    return "`{$column->name}` {$type}{$null}{$default}{$pk}";
                })->implode(', ');
                
                // Create new table with updated_at timestamp
                DB::statement("CREATE TABLE order_files_new (
                    $columns, 
                    updated_at TIMESTAMP NULL
                )");
                
                // Copy data
                DB::statement("INSERT INTO order_files_new SELECT *, NULL FROM order_files");
                
                // Drop old table
                Schema::drop('order_files');
                
                // Rename new table
                Schema::rename('order_files_new', 'order_files');
            }
        } else {
            // For other databases
            Schema::table('order_files', function (Blueprint $table) {
                if (!Schema::hasColumn('order_files', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('order_files', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For SQLite, we can't easily drop columns, so we'll skip the down migration
    }
};
