<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->nullable()->index()->after('code');
            }
            if (! Schema::hasColumn('products', 'model')) {
                $table->string('model')->nullable()->after('name');
            }
            if (! Schema::hasColumn('products', 'color')) {
                $table->string('color')->nullable()->after('model');
            }
            if (! Schema::hasColumn('products', 'storage_variant')) {
                $table->string('storage_variant')->nullable()->after('color');
            }
            if (! Schema::hasColumn('products', 'serial_number')) {
                $table->string('serial_number')->nullable()->index()->after('storage_variant');
            }
            if (! Schema::hasColumn('products', 'supplier_id')) {
                $table->unsignedBigInteger('supplier_id')->nullable()->index()->after('brand_id');
            }
            if (! Schema::hasColumn('products', 'profit_margin')) {
                $table->decimal('profit_margin', 10, 2)->default(0)->after('min_price');
            }
        });

        Schema::table('clients', function (Blueprint $table) {
            if (! Schema::hasColumn('clients', 'cnic')) {
                $table->string('cnic')->nullable()->index()->after('phone');
            }
            if (! Schema::hasColumn('clients', 'credit_balance')) {
                $table->decimal('credit_balance', 15, 2)->default(0)->after('credit_limit');
            }
            if (! Schema::hasColumn('clients', 'due_date')) {
                $table->date('due_date')->nullable()->after('credit_balance');
            }
            if (! Schema::hasColumn('clients', 'payment_reminder_status')) {
                $table->string('payment_reminder_status')->default('none')->after('due_date');
            }
        });

        $now = now();

        DB::table('currencies')->updateOrInsert(
            ['code' => 'PKR'],
            ['name' => 'Pakistani Rupee', 'symbol' => 'Rs', 'updated_at' => $now, 'created_at' => $now]
        );
        $currencyId = DB::table('currencies')->where('code', 'PKR')->value('id');

        DB::table('warehouses')->updateOrInsert(
            ['name' => 'Main Shop Stock'],
            [
                'city' => 'Cantt',
                'mobile' => '03037444089',
                'zip' => null,
                'email' => null,
                'country' => 'Pakistan',
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );
        $warehouseId = DB::table('warehouses')->where('name', 'Main Shop Stock')->value('id');

        foreach (['Cash', 'Bank Transfer', 'EasyPaisa', 'JazzCash', 'Credit/Loan'] as $method) {
            DB::table('payment_methods')->updateOrInsert(
                ['name' => $method],
                ['name' => $method, 'updated_at' => $now, 'created_at' => $now]
            );
        }
        $cashMethodId = DB::table('payment_methods')->where('name', 'Cash')->value('id');

        $categories = [
            'MOB-PHONES' => 'Mobile Phones',
            'USED-PHONES' => 'Used Phones',
            'ACCESSORIES' => 'Accessories',
            'PARTS' => 'Parts',
            'CHARGERS' => 'Chargers',
            'LCDS' => 'LCDs',
            'BATTERIES' => 'Batteries',
            'COVERS' => 'Covers',
            'AIRPODS' => 'AirPods',
            'SPEAKERS' => 'Speakers',
            'WATCHES' => 'Smart Watches',
            'REPAIR-ACC' => 'Repair Accessories',
        ];

        foreach ($categories as $code => $name) {
            DB::table('categories')->updateOrInsert(
                ['code' => $code],
                ['name' => $name, 'icon' => null, 'updated_at' => $now, 'created_at' => $now]
            );
        }

        DB::table('settings')->updateOrInsert(
            ['id' => 1],
            [
                'currency_id' => $currencyId,
                'CompanyName' => "Adeel I Phone LAB & Part's",
                'CompanyPhone' => 'M Awais - 03037444089',
                'CompanyAdress' => 'Shop#134 1st Floor Mall Plaza Cantt',
                'warehouse_id' => $warehouseId,
                'default_payment_method_id' => $cashMethodId,
                'footer' => 'Thank you for shopping with Adeel I Phone LAB & Part\'s.',
                'invoice_footer' => 'Thank you for shopping with Adeel I Phone LAB & Part\'s.',
                'app_name' => "Adeel I Phone LAB & Part's",
                'page_title_suffix' => "Adeel I Phone LAB & Part's",
                'login_hero_title' => 'Mobile Shop POS & Accounting',
                'login_hero_subtitle' => 'Sales, repair jobs, stock, customer credit, and accounting in one focused system.',
                'login_panel_title' => "Adeel I Phone LAB & Part's",
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        foreach ([
            ['name' => 'Admin', 'label' => 'Administrator', 'description' => 'Full system access'],
            ['name' => 'Manager', 'label' => 'Manager', 'description' => 'Manage stock, sales, purchases, reports, and customers'],
            ['name' => 'Cashier', 'label' => 'Cashier', 'description' => 'Counter sales, receipts, payments, and customer lookup'],
        ] as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role['name']],
                ['label' => $role['label'], 'description' => $role['description'], 'status' => 1, 'updated_at' => $now, 'created_at' => $now]
            );
        }

        $this->dropIrrelevantModuleTables();
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            foreach (['sku', 'model', 'color', 'storage_variant', 'serial_number', 'supplier_id', 'profit_margin'] as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('clients', function (Blueprint $table) {
            foreach (['cnic', 'credit_balance', 'due_date', 'payment_reminder_status'] as $column) {
                if (Schema::hasColumn('clients', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    private function dropIrrelevantModuleTables(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ([
            'asset_categories', 'assets',
            'attendances', 'leave_types', 'leaves', 'holidays', 'office_shifts', 'payrolls',
            'departments', 'designations', 'employee_accounts', 'employee_experiences', 'employee_project', 'employee_task', 'employees',
            'bookings',
            'commission_receipts', 'commission_rules', 'commission_programs', 'sale_commissions', 'sales_agents',
            'companies', 'contract_attachments', 'contract_comments', 'contract_notes', 'contract_renewals', 'contract_tasks', 'contract_templates', 'contracts',
            'knowledge_base_article_feedbacks', 'knowledge_base_articles', 'knowledge_base_article_groups',
            'projects', 'tasks',
            'quick_books_audits', 'quick_books_tokens',
            'report_questions',
            'shipments', 'subscriptions',
            'sync_jobs',
            'transfer_detail_batches', 'transfer_details', 'transfers',
            'webhook_deliveries', 'webhook_incoming_logs', 'webhooks',
            'woocommerce_logs', 'woocommerce_settings',
            'customer_display_tokens', 'customer_display_sessions',
            'ecommerce_clients', 'store_settings', 'store_banners', 'store_collections', 'store_collection_product', 'store_messages', 'store_subscribers', 'store_order_snapshots', 'store_invite_codes',
        ] as $table) {
            Schema::dropIfExists($table);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
