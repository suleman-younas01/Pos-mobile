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
        // Drop child tables first to satisfy foreign key constraints.
        Schema::dropIfExists('online_order_items');
        Schema::dropIfExists('online_orders');
        Schema::dropIfExists('collection_product');
        Schema::dropIfExists('collections');
        Schema::dropIfExists('store_banners');
        Schema::dropIfExists('subscribers');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('ecommerce_clients');
        Schema::dropIfExists('invite_codes');
        Schema::dropIfExists('store_settings');

        // Remove store-only product columns if present.
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $columns = [
                    'is_featured',
                    'hide_from_online_store',
                    'is_preorder',
                    'preorder_available_date',
                    'preorder_limit',
                    'preorder_note',
                ];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('products', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        // Remove store-only settings column if present.
        if (Schema::hasTable('settings') && Schema::hasColumn('settings', 'customize_button_visible')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn('customize_button_visible');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally left minimal: restoring full online-store schema is not part of this rollback.
    }
};
