<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DemoDataSeeder extends Seeder
{
    /**
     * Seed rich demo data so dashboards and pages look populated.
     */
    public function run()
    {
        // Keep seeding idempotent across repeated runs without migrate:fresh.
        if (DB::table('categories')->where('code', 'DEMO-CAT-001')->exists()) {
            return;
        }

        $now = now();

        $warehouseId = DB::table('warehouses')->value('id');
        if (! $warehouseId) {
            $warehouseId = DB::table('warehouses')->insertGetId([
                'name' => 'Main Warehouse',
                'city' => 'Karachi',
                'country' => 'Pakistan',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $unitPieceId = DB::table('units')->insertGetId([
            'name' => 'Piece',
            'ShortName' => 'pc',
            'base_unit' => null,
            'operator' => '*',
            'operator_value' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $unitBoxId = DB::table('units')->insertGetId([
            'name' => 'Box',
            'ShortName' => 'box',
            'base_unit' => $unitPieceId,
            'operator' => '*',
            'operator_value' => 10,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $categories = [];
        $categories[] = DB::table('categories')->insertGetId([
            'code' => 'DEMO-CAT-001',
            'name' => 'Electronics',
            'icon' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $categories[] = DB::table('categories')->insertGetId([
            'code' => 'DEMO-CAT-002',
            'name' => 'Groceries',
            'icon' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $categories[] = DB::table('categories')->insertGetId([
            'code' => 'DEMO-CAT-003',
            'name' => 'Office Supplies',
            'icon' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $subCategoryByCategory = [];
        $subCategoryByCategory[$categories[0]] = DB::table('subcategories')->insertGetId([
            'category_id' => $categories[0],
            'name' => 'Accessories',
            'description' => 'Demo accessories',
            'status' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $subCategoryByCategory[$categories[1]] = DB::table('subcategories')->insertGetId([
            'category_id' => $categories[1],
            'name' => 'Beverages',
            'description' => 'Demo beverages',
            'status' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $subCategoryByCategory[$categories[2]] = DB::table('subcategories')->insertGetId([
            'category_id' => $categories[2],
            'name' => 'Stationery',
            'description' => 'Demo stationery',
            'status' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $brandIds = [];
        $brandIds[] = DB::table('brands')->insertGetId([
            'name' => 'NovaTech',
            'description' => 'Demo electronics brand',
            'image' => 'no-image.png',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $brandIds[] = DB::table('brands')->insertGetId([
            'name' => 'FreshMills',
            'description' => 'Demo grocery brand',
            'image' => 'no-image.png',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $brandIds[] = DB::table('brands')->insertGetId([
            'name' => 'PaperCraft',
            'description' => 'Demo office brand',
            'image' => 'no-image.png',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $providerA = DB::table('providers')->insertGetId([
            'name' => 'Alpha Distributors',
            'code' => 'SUP-001',
            'email' => 'alpha@example.com',
            'phone' => '03001234561',
            'country' => 'Pakistan',
            'city' => 'Karachi',
            'adresse' => 'Shahrah-e-Faisal',
            'opening_balance' => 0,
            'credit_limit' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $providerB = DB::table('providers')->insertGetId([
            'name' => 'Metro Wholesale',
            'code' => 'SUP-002',
            'email' => 'metro@example.com',
            'phone' => '03001234562',
            'country' => 'Pakistan',
            'city' => 'Karachi',
            'adresse' => 'Tariq Road',
            'opening_balance' => 0,
            'credit_limit' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('clients')->insert([
            [
                'name' => 'Ayesha Traders',
                'firstname' => 'Ayesha',
                'lastname' => 'Khan',
                'code' => 'CL-1001',
                'email' => 'ayesha@example.com',
                'country' => 'Pakistan',
                'city' => 'Karachi',
                'state' => 'Sindh',
                'zip' => '75400',
                'phone' => '03002223331',
                'adresse' => 'Gulshan-e-Iqbal',
                'is_royalty_eligible' => 1,
                'points' => 120,
                'opening_balance' => 0,
                'credit_limit' => 50000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Blue Ocean Mart',
                'firstname' => 'Blue',
                'lastname' => 'Ocean',
                'code' => 'CL-1002',
                'email' => 'blueocean@example.com',
                'country' => 'Pakistan',
                'city' => 'Lahore',
                'state' => 'Punjab',
                'zip' => '54000',
                'phone' => '03002223332',
                'adresse' => 'Johar Town',
                'is_royalty_eligible' => 1,
                'points' => 40,
                'opening_balance' => 0,
                'credit_limit' => 30000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'City Books',
                'firstname' => 'City',
                'lastname' => 'Books',
                'code' => 'CL-1003',
                'email' => 'citybooks@example.com',
                'country' => 'Pakistan',
                'city' => 'Islamabad',
                'state' => 'ICT',
                'zip' => '44000',
                'phone' => '03002223333',
                'adresse' => 'Blue Area',
                'is_royalty_eligible' => 1,
                'points' => 75,
                'opening_balance' => 0,
                'credit_limit' => 25000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $products = [
            [
                'code' => 'DEMO-PRD-001', 'name' => 'Wireless Mouse', 'cat' => $categories[0], 'sub' => $subCategoryByCategory[$categories[0]], 'brand' => $brandIds[0],
                'cost' => 950, 'price' => 1299, 'wholesale' => 1190, 'min' => 1040, 'qty' => 48,
            ],
            [
                'code' => 'DEMO-PRD-002', 'name' => 'USB Keyboard', 'cat' => $categories[0], 'sub' => $subCategoryByCategory[$categories[0]], 'brand' => $brandIds[0],
                'cost' => 1400, 'price' => 1899, 'wholesale' => 1740, 'min' => 1550, 'qty' => 32,
            ],
            [
                'code' => 'DEMO-PRD-003', 'name' => 'HDMI Cable 2m', 'cat' => $categories[0], 'sub' => $subCategoryByCategory[$categories[0]], 'brand' => $brandIds[0],
                'cost' => 320, 'price' => 550, 'wholesale' => 499, 'min' => 380, 'qty' => 80,
            ],
            [
                'code' => 'DEMO-PRD-004', 'name' => 'Organic Green Tea Box', 'cat' => $categories[1], 'sub' => $subCategoryByCategory[$categories[1]], 'brand' => $brandIds[1],
                'cost' => 260, 'price' => 399, 'wholesale' => 360, 'min' => 300, 'qty' => 120,
            ],
            [
                'code' => 'DEMO-PRD-005', 'name' => 'Instant Coffee Jar', 'cat' => $categories[1], 'sub' => $subCategoryByCategory[$categories[1]], 'brand' => $brandIds[1],
                'cost' => 520, 'price' => 760, 'wholesale' => 700, 'min' => 590, 'qty' => 70,
            ],
            [
                'code' => 'DEMO-PRD-006', 'name' => 'A4 Printing Paper (500)', 'cat' => $categories[2], 'sub' => $subCategoryByCategory[$categories[2]], 'brand' => $brandIds[2],
                'cost' => 980, 'price' => 1350, 'wholesale' => 1250, 'min' => 1080, 'qty' => 55,
            ],
            [
                'code' => 'DEMO-PRD-007', 'name' => 'Gel Pen Pack (10)', 'cat' => $categories[2], 'sub' => $subCategoryByCategory[$categories[2]], 'brand' => $brandIds[2],
                'cost' => 180, 'price' => 299, 'wholesale' => 260, 'min' => 210, 'qty' => 140,
            ],
            [
                'code' => 'DEMO-PRD-008', 'name' => 'Notebook A5', 'cat' => $categories[2], 'sub' => $subCategoryByCategory[$categories[2]], 'brand' => $brandIds[2],
                'cost' => 110, 'price' => 190, 'wholesale' => 170, 'min' => 130, 'qty' => 200,
            ],
        ];

        $productIdByCode = [];
        $hasIsFeatured = Schema::hasColumn('products', 'is_featured');
        $hasHideFromOnlineStore = Schema::hasColumn('products', 'hide_from_online_store');
        foreach ($products as $p) {
            $productRow = [
                'type' => 'is_single',
                'code' => $p['code'],
                'Type_barcode' => 'CODE128',
                'name' => $p['name'],
                'cost' => $p['cost'],
                'price' => $p['price'],
                'wholesale_price' => $p['wholesale'],
                'min_price' => $p['min'],
                'category_id' => $p['cat'],
                'sub_category_id' => $p['sub'],
                'brand_id' => $p['brand'],
                'unit_id' => $unitPieceId,
                'unit_sale_id' => $unitPieceId,
                'unit_purchase_id' => $unitBoxId,
                'TaxNet' => 0,
                'tax_method' => 1,
                'discount' => 0,
                'discount_method' => 2,
                'image' => 'no-image.png',
                'note' => 'Demo product for initial project preview',
                'stock_alert' => 10,
                'is_variant' => 0,
                'is_imei' => 0,
                'not_selling' => 0,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if ($hasIsFeatured) {
                $productRow['is_featured'] = 0;
            }
            if ($hasHideFromOnlineStore) {
                $productRow['hide_from_online_store'] = 0;
            }

            $productId = DB::table('products')->insertGetId($productRow);

            $productIdByCode[$p['code']] = $productId;

            DB::table('product_warehouse')->insert([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'product_variant_id' => null,
                'qte' => $p['qty'],
                'manage_stock' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('category_product')->insert([
                'product_id' => $productId,
                'category_id' => $p['cat'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('product_subcategory')->insert([
                'product_id' => $productId,
                'sub_category_id' => $p['sub'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $clientAId = DB::table('clients')->where('code', 'CL-1001')->value('id');
        $clientBId = DB::table('clients')->where('code', 'CL-1002')->value('id');

        // Purchases
        $purchaseDateA = now()->subDays(12)->toDateString();
        $purchaseDateB = now()->subDays(5)->toDateString();

        $purchaseARef = 'DEMO-PUR-001';
        $purchaseAGrand = 26200; // 950*10 + 1400*8 + 260*15 + 520*3
        $purchaseAPaid = 18000;

        $purchaseAId = DB::table('purchases')->insertGetId([
            'user_id' => 1,
            'Ref' => $purchaseARef,
            'date' => $purchaseDateA,
            'time' => '10:00',
            'provider_id' => $providerA,
            'warehouse_id' => $warehouseId,
            'tax_rate' => 0,
            'TaxNet' => 0,
            'discount' => 0,
            'shipping' => 0,
            'GrandTotal' => $purchaseAGrand,
            'paid_amount' => $purchaseAPaid,
            'statut' => 'received',
            'payment_statut' => 'partial',
            'notes' => 'Demo purchase #1',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('purchase_details')->insert([
            [
                'cost' => 950, 'purchase_unit_id' => $unitPieceId, 'TaxNet' => 0, 'tax_method' => 1, 'discount' => 0, 'discount_method' => 2,
                'purchase_id' => $purchaseAId, 'product_id' => $productIdByCode['DEMO-PRD-001'], 'product_variant_id' => null, 'imei_number' => null, 'total' => 9500, 'quantity' => 10,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'cost' => 1400, 'purchase_unit_id' => $unitPieceId, 'TaxNet' => 0, 'tax_method' => 1, 'discount' => 0, 'discount_method' => 2,
                'purchase_id' => $purchaseAId, 'product_id' => $productIdByCode['DEMO-PRD-002'], 'product_variant_id' => null, 'imei_number' => null, 'total' => 11200, 'quantity' => 8,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'cost' => 260, 'purchase_unit_id' => $unitPieceId, 'TaxNet' => 0, 'tax_method' => 1, 'discount' => 0, 'discount_method' => 2,
                'purchase_id' => $purchaseAId, 'product_id' => $productIdByCode['DEMO-PRD-004'], 'product_variant_id' => null, 'imei_number' => null, 'total' => 3900, 'quantity' => 15,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'cost' => 520, 'purchase_unit_id' => $unitPieceId, 'TaxNet' => 0, 'tax_method' => 1, 'discount' => 0, 'discount_method' => 2,
                'purchase_id' => $purchaseAId, 'product_id' => $productIdByCode['DEMO-PRD-005'], 'product_variant_id' => null, 'imei_number' => null, 'total' => 1560, 'quantity' => 3,
                'created_at' => $now, 'updated_at' => $now,
            ],
        ]);

        DB::table('payment_purchases')->insert([
            'user_id' => 1,
            'date' => $purchaseDateA,
            'Ref' => 'DEMO-PP-001',
            'purchase_id' => $purchaseAId,
            'account_id' => null,
            'montant' => $purchaseAPaid,
            'change' => 0,
            'payment_method_id' => 2,
            'notes' => 'Cash payment demo',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $purchaseBRef = 'DEMO-PUR-002';
        $purchaseBGrand = 19100; // 980*10 + 180*20 + 110*20 + 320*11

        $purchaseBId = DB::table('purchases')->insertGetId([
            'user_id' => 1,
            'Ref' => $purchaseBRef,
            'date' => $purchaseDateB,
            'time' => '14:30',
            'provider_id' => $providerB,
            'warehouse_id' => $warehouseId,
            'tax_rate' => 0,
            'TaxNet' => 0,
            'discount' => 0,
            'shipping' => 0,
            'GrandTotal' => $purchaseBGrand,
            'paid_amount' => $purchaseBGrand,
            'statut' => 'received',
            'payment_statut' => 'paid',
            'notes' => 'Demo purchase #2',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('purchase_details')->insert([
            [
                'cost' => 980, 'purchase_unit_id' => $unitPieceId, 'TaxNet' => 0, 'tax_method' => 1, 'discount' => 0, 'discount_method' => 2,
                'purchase_id' => $purchaseBId, 'product_id' => $productIdByCode['DEMO-PRD-006'], 'product_variant_id' => null, 'imei_number' => null, 'total' => 9800, 'quantity' => 10,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'cost' => 180, 'purchase_unit_id' => $unitPieceId, 'TaxNet' => 0, 'tax_method' => 1, 'discount' => 0, 'discount_method' => 2,
                'purchase_id' => $purchaseBId, 'product_id' => $productIdByCode['DEMO-PRD-007'], 'product_variant_id' => null, 'imei_number' => null, 'total' => 3600, 'quantity' => 20,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'cost' => 110, 'purchase_unit_id' => $unitPieceId, 'TaxNet' => 0, 'tax_method' => 1, 'discount' => 0, 'discount_method' => 2,
                'purchase_id' => $purchaseBId, 'product_id' => $productIdByCode['DEMO-PRD-008'], 'product_variant_id' => null, 'imei_number' => null, 'total' => 2200, 'quantity' => 20,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'cost' => 320, 'purchase_unit_id' => $unitPieceId, 'TaxNet' => 0, 'tax_method' => 1, 'discount' => 0, 'discount_method' => 2,
                'purchase_id' => $purchaseBId, 'product_id' => $productIdByCode['DEMO-PRD-003'], 'product_variant_id' => null, 'imei_number' => null, 'total' => 3520, 'quantity' => 11,
                'created_at' => $now, 'updated_at' => $now,
            ],
        ]);

        DB::table('payment_purchases')->insert([
            'user_id' => 1,
            'date' => $purchaseDateB,
            'Ref' => 'DEMO-PP-002',
            'purchase_id' => $purchaseBId,
            'account_id' => null,
            'montant' => $purchaseBGrand,
            'change' => 0,
            'payment_method_id' => 6,
            'notes' => 'Bank transfer demo',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Sales
        $saleDateA = now()->subDays(4)->toDateString();
        $saleDateB = now()->subDays(2)->toDateString();

        $saleARef = 'DEMO-SAL-001';
        $saleAGrand = 9578; // 1299*3 + 1899*2 + 399*2 + 550*2
        $saleAPaid = $saleAGrand;

        $saleAId = DB::table('sales')->insertGetId([
            'user_id' => 1,
            'sales_agent_id' => null,
            'date' => $saleDateA,
            'time' => '12:15',
            'Ref' => $saleARef,
            'is_pos' => 1,
            'client_id' => $clientAId,
            'warehouse_id' => $warehouseId,
            'tax_rate' => 0,
            'TaxNet' => 0,
            'discount' => 0,
            'discount_Method' => '2',
            'shipping' => 0,
            'GrandTotal' => $saleAGrand,
            'paid_amount' => $saleAPaid,
            'payment_statut' => 'paid',
            'statut' => 'completed',
            'shipping_status' => null,
            'notes' => 'Demo POS sale',
            'used_points' => 0,
            'earned_points' => 0,
            'discount_from_points' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('sale_details')->insert([
            [
                'date' => $saleDateA, 'sale_id' => $saleAId, 'product_id' => $productIdByCode['DEMO-PRD-001'], 'product_variant_id' => null, 'imei_number' => null,
                'price' => 1299, 'sale_unit_id' => $unitPieceId, 'TaxNet' => 0, 'tax_method' => 1, 'discount' => 0, 'discount_method' => 2, 'price_type' => 'retail',
                'warranty_date' => null, 'guarantee_date' => null, 'total' => 3897, 'quantity' => 3, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'date' => $saleDateA, 'sale_id' => $saleAId, 'product_id' => $productIdByCode['DEMO-PRD-002'], 'product_variant_id' => null, 'imei_number' => null,
                'price' => 1899, 'sale_unit_id' => $unitPieceId, 'TaxNet' => 0, 'tax_method' => 1, 'discount' => 0, 'discount_method' => 2, 'price_type' => 'retail',
                'warranty_date' => null, 'guarantee_date' => null, 'total' => 3798, 'quantity' => 2, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'date' => $saleDateA, 'sale_id' => $saleAId, 'product_id' => $productIdByCode['DEMO-PRD-004'], 'product_variant_id' => null, 'imei_number' => null,
                'price' => 399, 'sale_unit_id' => $unitPieceId, 'TaxNet' => 0, 'tax_method' => 1, 'discount' => 0, 'discount_method' => 2, 'price_type' => 'retail',
                'warranty_date' => null, 'guarantee_date' => null, 'total' => 798, 'quantity' => 2, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'date' => $saleDateA, 'sale_id' => $saleAId, 'product_id' => $productIdByCode['DEMO-PRD-003'], 'product_variant_id' => null, 'imei_number' => null,
                'price' => 550, 'sale_unit_id' => $unitPieceId, 'TaxNet' => 0, 'tax_method' => 1, 'discount' => 0, 'discount_method' => 2, 'price_type' => 'retail',
                'warranty_date' => null, 'guarantee_date' => null, 'total' => 1100, 'quantity' => 2, 'created_at' => $now, 'updated_at' => $now,
            ],
        ]);

        DB::table('payment_sales')->insert([
            'user_id' => 1,
            'date' => $saleDateA,
            'Ref' => 'DEMO-PS-001',
            'sale_id' => $saleAId,
            'account_id' => null,
            'montant' => $saleAPaid,
            'change' => 0,
            'payment_method_id' => 2,
            'notes' => 'Full cash payment',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $saleBRef = 'DEMO-SAL-002';
        $saleBGrand = 3848; // 760*2 + 1350*1 + 299*2 + 190*2
        $saleBPaid = 2000;

        $saleBId = DB::table('sales')->insertGetId([
            'user_id' => 1,
            'sales_agent_id' => null,
            'date' => $saleDateB,
            'time' => '16:40',
            'Ref' => $saleBRef,
            'is_pos' => 0,
            'client_id' => $clientBId,
            'warehouse_id' => $warehouseId,
            'tax_rate' => 0,
            'TaxNet' => 0,
            'discount' => 0,
            'discount_Method' => '2',
            'shipping' => 0,
            'GrandTotal' => $saleBGrand,
            'paid_amount' => $saleBPaid,
            'payment_statut' => 'partial',
            'statut' => 'completed',
            'shipping_status' => null,
            'notes' => 'Demo invoice sale',
            'used_points' => 0,
            'earned_points' => 0,
            'discount_from_points' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('sale_details')->insert([
            [
                'date' => $saleDateB, 'sale_id' => $saleBId, 'product_id' => $productIdByCode['DEMO-PRD-005'], 'product_variant_id' => null, 'imei_number' => null,
                'price' => 760, 'sale_unit_id' => $unitPieceId, 'TaxNet' => 0, 'tax_method' => 1, 'discount' => 0, 'discount_method' => 2, 'price_type' => 'retail',
                'warranty_date' => null, 'guarantee_date' => null, 'total' => 1520, 'quantity' => 2, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'date' => $saleDateB, 'sale_id' => $saleBId, 'product_id' => $productIdByCode['DEMO-PRD-006'], 'product_variant_id' => null, 'imei_number' => null,
                'price' => 1350, 'sale_unit_id' => $unitPieceId, 'TaxNet' => 0, 'tax_method' => 1, 'discount' => 0, 'discount_method' => 2, 'price_type' => 'retail',
                'warranty_date' => null, 'guarantee_date' => null, 'total' => 1350, 'quantity' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'date' => $saleDateB, 'sale_id' => $saleBId, 'product_id' => $productIdByCode['DEMO-PRD-007'], 'product_variant_id' => null, 'imei_number' => null,
                'price' => 299, 'sale_unit_id' => $unitPieceId, 'TaxNet' => 0, 'tax_method' => 1, 'discount' => 0, 'discount_method' => 2, 'price_type' => 'retail',
                'warranty_date' => null, 'guarantee_date' => null, 'total' => 598, 'quantity' => 2, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'date' => $saleDateB, 'sale_id' => $saleBId, 'product_id' => $productIdByCode['DEMO-PRD-008'], 'product_variant_id' => null, 'imei_number' => null,
                'price' => 190, 'sale_unit_id' => $unitPieceId, 'TaxNet' => 0, 'tax_method' => 1, 'discount' => 0, 'discount_method' => 2, 'price_type' => 'retail',
                'warranty_date' => null, 'guarantee_date' => null, 'total' => 380, 'quantity' => 2, 'created_at' => $now, 'updated_at' => $now,
            ],
        ]);

        DB::table('payment_sales')->insert([
            'user_id' => 1,
            'date' => $saleDateB,
            'Ref' => 'DEMO-PS-002',
            'sale_id' => $saleBId,
            'account_id' => null,
            'montant' => $saleBPaid,
            'change' => 0,
            'payment_method_id' => 6,
            'notes' => 'Partial bank payment',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
