<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = DB::select('SHOW TABLES');
        $key = 'Tables_in_' . DB::getDatabaseName();

        foreach ($tables as $table) {
            $tableName = $table->$key;

            // Skip system tables or views if any
            if (Schema::hasTable($tableName)) {
                DB::statement("ALTER TABLE `$tableName` ENGINE=InnoDB");
            }
        }
    }
};
