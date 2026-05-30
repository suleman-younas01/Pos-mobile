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
        if (! Schema::hasTable('products')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'sub_category_id')) {
                try {
                    $table->dropForeign('products_sub_category_id_foreign');
                } catch (\Throwable $e) {
                    // ignore if foreign key name differs or already removed
                }

                try {
                    $table->dropIndex('sub_category_id');
                } catch (\Throwable $e) {
                    // ignore if index is missing or has a different name
                }

                $table->dropColumn('sub_category_id');
            }

            if (Schema::hasColumn('products', 'TaxNet')) {
                $table->dropColumn('TaxNet');
            }

            if (Schema::hasColumn('products', 'tax_method')) {
                $table->dropColumn('tax_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'sub_category_id')) {
                $table->integer('sub_category_id')->nullable()->after('category_id');
                $table->index('sub_category_id', 'sub_category_id');
            }

            if (! Schema::hasColumn('products', 'TaxNet')) {
                $table->decimal('TaxNet', 15, 2)->nullable()->default(0)->after('unit_purchase_id');
            }

            if (! Schema::hasColumn('products', 'tax_method')) {
                $table->string('tax_method', 192)->nullable()->default('1')->after('TaxNet');
            }
        });
    }
};

