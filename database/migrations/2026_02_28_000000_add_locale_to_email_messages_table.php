<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('email_messages', 'locale')) {
            Schema::table('email_messages', function (Blueprint $table) {
                $table->string('locale', 10)->default('en')->after('name');
            });
            \DB::table('email_messages')->whereNull('locale')->update(['locale' => 'en']);
        }

        // SQLite compatible - skip unique index check
        try {
            Schema::table('email_messages', function (Blueprint $table) {
                $table->unique(['name', 'locale'], 'email_messages_name_locale_unique');
            });
        } catch (\Exception $e) {
            // Index already exists, ignore
        }
    }

    public function down(): void
    {
        try {
            Schema::table('email_messages', function (Blueprint $table) {
                $table->dropUnique('email_messages_name_locale_unique');
            });
        } catch (\Exception $e) {
            // ignore
        }

        if (Schema::hasColumn('email_messages', 'locale')) {
            Schema::table('email_messages', function (Blueprint $table) {
                $table->dropColumn('locale');
            });
        }
    }
};