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
        if (!Schema::hasColumn('sms_messages', 'locale')) {
            Schema::table('sms_messages', function (Blueprint $table) {
                $table->string('locale', 10)->default('en')->after('name');
            });
            \DB::table('sms_messages')->whereNull('locale')->update(['locale' => 'en']);
        }

        // Ensure no NULL or empty name (legacy rows) so unique index can be added in next migration
        try {
            \DB::statement("UPDATE sms_messages SET name = CONCAT('legacy_', id) WHERE name IS NULL OR TRIM(COALESCE(name, '')) = ''");
        } catch (\Throwable $e) {
            foreach (\DB::table('sms_messages')
                ->select('id', 'name')
                ->whereNull('name')
                ->orWhereRaw("TRIM(COALESCE(name, '')) = ''")
                ->get() as $message) {
                \DB::table('sms_messages')
                    ->where('id', $message->id)
                    ->update(['name' => 'legacy_' . $message->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('sms_messages', 'locale')) {
            Schema::table('sms_messages', function (Blueprint $table) {
                $table->dropColumn('locale');
            });
        }
    }
};
