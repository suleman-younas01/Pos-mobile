<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('service_jobs', 'scheduled_date')) {
            try {
                DB::statement('ALTER TABLE service_jobs MODIFY scheduled_date DATETIME NULL');
            } catch (\Throwable $e) {
                // SQLite does not support ALTER TABLE MODIFY; ignore on SQLite.
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('service_jobs', 'scheduled_date')) {
            try {
                DB::statement('ALTER TABLE service_jobs MODIFY scheduled_date DATE NULL');
            } catch (\Throwable $e) {
                // SQLite does not support ALTER TABLE MODIFY; ignore on SQLite.
            }
        }
    }
};
