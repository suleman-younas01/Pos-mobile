<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Lightweight reachability probe for SPA/offline detection.
// Public by design: returns no sensitive data.
Route::get('/ping', function () {
    return response()->json([
        'ok' => true,
        'ts' => now()->timestamp,
    ]);
});

// --------------------------- Reset Password  ---------------------------

Route::group([
    'prefix' => 'password',
], function () {
    Route::post('create', 'PasswordResetController@create');
    Route::post('reset', 'PasswordResetController@reset');
});

Route::get('/products_clean_names', 'ProductsController@cleanNames');

Route::post('getAccessToken', 'AuthController@getAccessToken');

Route::get('/get-logo-setting', function () {
    $setting = \App\Models\Setting::first();

    return response()->json([
        'logo' => $setting->logo ?? null,
    ]);
});

Route::get('/translations/{locale}', function ($locale) {
    $translations = \DB::table('translations')
        ->where('locale', $locale)
        ->pluck('value', 'key');

    return response()->json($translations);
});

Route::get('/languages', 'LanguageController@load_language');


Route::middleware(['auth:api', 'Is_Active', 'request.safety', 'token.timeout'])->group(function () {
    Route::get('dashboard_data', 'DashboardController@dashboard_data');

    Route::get('/languages_setting', 'LanguageController@index');
    Route::post('/languages_setting', 'LanguageController@store');
    Route::put('/languages_setting/{language}', 'LanguageController@update');
    Route::delete('/languages_setting/{language}', 'LanguageController@destroy');
    Route::post('/languages_setting/{id}/set-default', 'LanguageController@setDefault');
    Route::post('/languages_setting/{id}/set-active', 'LanguageController@setLocaleActive');
    Route::post('/languages_setting/set-default/{locale}', 'LanguageController@setDefaultByLocale');

    // Sync Vue i18n locale to Laravel session for Blade PDFs (no translations_settings permission required)
    Route::post('/sync-locale', 'LocaleSyncController@sync');
    
    Route::get('/translations_setting/{locale}', 'LanguageController@get_translate');
    Route::put('/translations_setting/{locale}', 'LanguageController@update_translate');
    Route::put('/translations_setting/{locale}', 'LanguageController@updateOrInsert');
    Route::delete('/translations_setting/{locale}/{key}', 'LanguageController@delete_translate');

    // -------------------------- Clear Cache ---------------------------

    Route::get('clear_cache', 'SettingsController@Clear_Cache');

    // ------------------------------- error_logs ------------------------\\

    Route::get('/error-logs', 'ErrorLogController@index')->name('error_logs.index');

    // -------------------------- Reports ---------------------------

    Route::get('report/client', 'ReportController@Client_Report');
    Route::get('report/client/{id}', 'ReportController@Client_Report_detail');
    Route::get('report/client_sales', 'ReportController@Sales_Client');
    Route::get('report/client_payments', 'ReportController@Payments_Client');
    Route::get('report/client_returns', 'ReportController@Returns_Client');
    Route::get('report/sales', 'ReportController@Report_Sales');
    Route::get('report/get_last_sales', 'ReportController@Get_last_Sales');
    Route::get('report/stock_alert', 'ReportController@Products_Alert');
    Route::get('report/payment_chart', 'ReportController@Payment_chart');
    Route::get('report/warehouse_report', 'ReportController@Warehouse_Report');
    Route::get('report/internal_location_report', 'ReportController@Internal_Location_Report');
    Route::get('report/sales_warehouse', 'ReportController@Sales_Warehouse');
    Route::get('report/returns_sale_warehouse', 'ReportController@Returns_Sale_Warehouse');
    Route::get('report/expenses_warehouse', 'ReportController@Expenses_Warehouse');
    Route::get('report/warhouse_count_stock', 'ReportController@Warhouse_Count_Stock');
    Route::get('report/report_today', 'ReportController@report_today');
    Route::get('report/count_quantity_alert', 'ReportController@count_quantity_alert');
    Route::get('report/profit_and_loss', 'ReportController@ProfitAndLoss');
    Route::get('report/report_dashboard', 'ReportController@report_dashboard');
    Route::get('report/top_products', 'ReportController@report_top_products');
    Route::get('report/top_customers', 'ReportController@report_top_customers');
    Route::get('report/product_report', 'ReportController@product_report');
    Route::get('report/sale_products_details', 'ReportController@sale_products_details');
    Route::get('report/product_sales_report', 'ReportController@product_sales_report');

    Route::get('report/users', 'ReportController@users_Report');
    Route::get('report/stock', 'ReportController@stock_Report');
    Route::get('report/get_sales_by_user', 'ReportController@get_sales_by_user');
    Route::get('report/get_sales_return_by_user', 'ReportController@get_sales_return_by_user');
    Route::get('report/get_adjustment_by_user', 'ReportController@get_adjustment_by_user');
    Route::get('report/get_sales_by_product', 'ReportController@get_sales_by_product');

    Route::get('report/get_sales_return_by_product', 'ReportController@get_sales_return_by_product');
    Route::get('report/get_adjustment_by_product', 'ReportController@get_adjustment_by_product');
    Route::get('report/client_pdf/{id}', 'ReportController@download_report_client_pdf');
    Route::get('report/analytics_summary', 'ReportController@analyticsSummary');

    Route::get('report/inventory_valuation_summary', 'ReportController@inventory_valuation_summary');
    Route::get('report/stock_inventory_valuation', 'ReportController@stock_inventory_valuation');
    Route::get('report/expenses_report', 'ReportController@expenses_report');
    Route::get('report/deposits_report', 'ReportController@deposits_report');
    Route::get('report/report_transactions', 'ReportController@report_transactions');
    Route::get('report/sales_by_category_report', 'ReportController@sales_by_category_report');
    Route::get('report/sales_by_brand_report', 'ReportController@sales_by_brand_report');
    Route::get('report/seller_report', 'ReportController@seller_report');
    Route::get('report/attendance_summary', 'ReportController@attendance_summary');
    Route::get('report/inactive_customers', 'ReportController@inactiveCustomers');
    Route::get('report/zero_sales_products', 'ReportController@zeroSalesProducts');
    Route::get('report/dead_stock', 'ReportController@deadStock');
    Route::get('report/expiry', 'ReportController@expiryReport');
    // Pharmacy batch reports — register (cross-batch list) + history (per-batch movement log).
    Route::get('report/batches/register', 'BatchReportController@register');
    Route::get('report/batches/{id}/history', 'BatchReportController@history');
    Route::get('report/draft_invoices', 'ReportController@draftInvoices');
    Route::get('report/discount_summary', 'ReportController@discountSummary');
    Route::get('report/tax_summary', 'ReportController@taxSummary');
    Route::get('report/stock_aging', 'ReportController@stockAging');
    Route::get('report/stock_aging/filters', 'ReportController@stockAgingFilters');
    Route::get('report/cash_flow_report', 'ReportController@cash_flow_report');
    Route::get('report/return_ratio_report', 'ReportController@return_ratio_report');
    Route::get('report/customer_loyalty_points', 'ReportController@customerLoyaltyPoints');
    Route::get('get_product_detail/{id}', 'ProductsController@Get_Products_Details');

    // Negative Stock
    Route::get('report/negative_stock', 'ReportController@negative_stock_report');

    // ------------------------------- Service & Maintenance ------------------------\\
    // Purely additive module: manages service jobs and dynamic checklists
    Route::resource('service_jobs', 'ServiceJobController');
    Route::resource('service_technicians', 'ServiceTechnicianController')->only(['index', 'store', 'update', 'destroy']);

    // Repair workflow actions
    Route::post('service_jobs/{id}/approve_quote', 'ServiceJobController@approveQuote');
    Route::post('service_jobs/{id}/decline_quote', 'ServiceJobController@declineQuote');
    Route::post('service_jobs/{id}/mark_delivered', 'ServiceJobController@markDelivered');

    // Service job payments
    Route::get('service_jobs/{service_job}/payments', 'ServiceJobPaymentController@index');
    Route::post('service_jobs/{service_job}/payments', 'ServiceJobPaymentController@store');
    Route::put('service_jobs/{service_job}/payments/{id}', 'ServiceJobPaymentController@update');
    Route::delete('service_jobs/{service_job}/payments/{id}', 'ServiceJobPaymentController@destroy');

    // Service job photos
    Route::get('service_jobs/{service_job}/photos', 'ServiceJobPhotoController@index');
    Route::post('service_jobs/{service_job}/photos', 'ServiceJobPhotoController@store');
    Route::delete('service_jobs/{service_job}/photos/{id}', 'ServiceJobPhotoController@destroy');

    Route::get('service_checklist/categories', 'ServiceChecklistController@categoriesIndex');
    Route::post('service_checklist/categories', 'ServiceChecklistController@categoriesStore');
    Route::put('service_checklist/categories/{id}', 'ServiceChecklistController@categoriesUpdate');
    Route::delete('service_checklist/categories/{id}', 'ServiceChecklistController@categoriesDestroy');

    Route::get('service_checklist/items', 'ServiceChecklistController@itemsIndex');
    Route::post('service_checklist/items', 'ServiceChecklistController@itemsStore');
    Route::put('service_checklist/items/{id}', 'ServiceChecklistController@itemsUpdate');
    Route::delete('service_checklist/items/{id}', 'ServiceChecklistController@itemsDestroy');

    Route::get('service_checklist/options', 'ServiceChecklistController@options');

    Route::get('report/service_jobs', 'ServiceReportController@serviceJobs');
    Route::get('report/service_checklist_completion', 'ServiceReportController@checklistCompletion');
    Route::get('report/customer_maintenance_history', 'ServiceReportController@customerMaintenanceHistory');

    // ------------------------------- payment_methods ------------------------\\
    // ------------------------------------------------------------------\\
    Route::resource('payment_methods', 'PaymentMethodController');
    // ------------------------------- CLIENTS --------------------------\\
    // ------------------------------------------------------------------\\

    Route::resource('clients', 'ClientController');
    Route::post('customers/import', 'ClientController@import');
    Route::get('get_clients_without_paginate', 'ClientController@Get_Clients_Without_Paginate');
    Route::post('clients/delete/by_selection', 'ClientController@delete_by_selection');
    Route::post('clients_pay_due', 'ClientController@clients_pay_due');
    Route::post('clients_pay_return_due', 'ClientController@pay_sale_return_due');
    Route::get('get_points_client/{id}', 'ClientController@getPoints');
    Route::post('customers/{id}/update-points', 'ClientController@updatePoints');
    Route::post('customers/{id}/adjust-opening-balance', 'ClientController@adjustOpeningBalance');

    // Customer Ledger (separate endpoints)
    Route::get('/sales_client', 'ClientController@salesByClient');
    Route::get('/payments_client', 'ClientController@paymentsByClient');
    Route::get('/returns_client', 'ClientController@returnsByClient');
    Route::get('/payment_returns_client', 'ClientController@paymentReturnsByClient');

    // Basic client info for header (optional but recommended)
    Route::get('clients/{id}/brief', 'ClientController@clientBrief');

    Route::get('/client_ledger_pdf', 'ClientController@export');

    // ------------------------------- CLIENTS Ecommerce--------------------------\\
    // ------------------------------------------------------------------\\

    // ------------------------------- Providers --------------------------\\
    // --------------------------------------------------------------------\\

    Route::resource('providers', 'ProvidersController');
    Route::post('suppliers/import', 'ProvidersController@import');

    Route::post('providers/delete/by_selection', 'ProvidersController@delete_by_selection');
    Route::post('pay_supplier_due', 'ProvidersController@pay_supplier_due');

    // ------------------------------- Custom Fields --------------------------\\
    // --------------------------------------------------------------------\\

    // Specific routes must come before resource route to avoid conflicts
    Route::get('custom-field-values', 'CustomFieldController@getValues');
    Route::post('custom-field-values', 'CustomFieldController@saveValues');
    Route::resource('custom-fields', 'CustomFieldController');

    // ---------------------- POS (point of sales) ----------------------\\
    // ------------------------------------------------------------------\\

    Route::post('pos/create_pos', 'PosController@CreatePOS');
    Route::get('pos/get_products_pos', 'PosController@GetProductsByParametre');
    Route::get('pos/get_products_pos_changes', 'PosController@GetProductsChanges');
    Route::get('pos/data_create_pos', 'PosController@GetELementPos');

    // ----------------------Draft -------------------------------------\\
    // ------------------------------------------------------------------\\
    Route::post('pos/create_draft', 'PosController@CreateDraft');
    Route::get('get_draft_sales', 'PosController@get_draft_sales');
    Route::delete('remove_draft_sale/{id}', 'PosController@remove_draft_sale');
    Route::get('pos/data_draft_convert_sale/{id}', 'PosController@data_draft_convert_sale');
    Route::post('pos/submit_sale_from_draft', 'PosController@submit_sale_from_draft');

    // ---------------------- Cash Registers (optional module) ----------------------\\
    // Fully additive; no changes to existing tables or logic
    Route::post('cash-registers/open', 'CashRegisterController@openRegister');
    Route::post('cash-registers/close', 'CashRegisterController@closeRegister');
    Route::get('cash-registers/current/{user_id}', 'CashRegisterController@getCurrentRegister');
    Route::post('cash-registers/cash-move', 'CashRegisterController@cashInOut');
    Route::get('report/cash_registers', 'CashRegisterController@report');
    Route::get('report/warranty_guarantee', 'ReportController@warrantyGuaranteeReport');
    // ------------------------------- PRODUCTS --------------------------\\
    // ------------------------------------------------------------------\\

    Route::resource('products', 'ProductsController');
    Route::post('products/{id}/duplicate', 'ProductsController@duplicate');
    Route::post('products/warehouse_locations', 'ProductsController@storeWarehouseLocation');
    Route::post('products/import/single', 'ProductsController@import_single_products')->middleware('auth:api');
    Route::post('products/import/variants', 'ProductsController@import_variant_products')->middleware('auth:api');
    Route::post('products/import/update-only', 'ProductsController@import_update_only')->middleware('auth:api');

    Route::get('get_Products_by_warehouse/{id}', 'ProductsController@Products_by_Warehouse');
    Route::get('get_product_detail_api/{id}', 'ProductsController@Get_Products_Details');
    Route::get('get_products_stock_alerts', 'ProductsController@Products_Alert');
    Route::get('barcode_create_page', 'ProductsController@Get_element_barcode');
    Route::post('products/delete/by_selection', 'ProductsController@delete_by_selection');
    Route::get('show_product_data/{id}/{variant_id}', 'ProductsController@show_product_data');
    Route::get('show_product_data/{id}/{variant_id}/{warehouse_id}', 'ProductsController@show_product_data');
    Route::get('get_products_materiels', 'ProductsController@get_products_materiels')->name('get_products_materiels');

    Route::get('opening-stock/import/meta', 'ProductsController@opening_stock_meta');
    Route::post('opening-stock/import/single', 'ProductsController@opening_stock_import_single');
    Route::post('opening-stock/import/variants', 'ProductsController@opening_stock_import_variants');

    // ---- count stock ----------
    Route::get('count_stock', 'ProductsController@count_stock_list');
    Route::post('store_count_stock', 'ProductsController@store_count_stock');

    // ------------------------------- Category --------------------------\\
    // ------------------------------------------------------------------\\

    Route::resource('categories', 'CategorieController');
    Route::post('categories/delete/by_selection', 'CategorieController@delete_by_selection');

    // ------------------------------- Units --------------------------\\
    // ------------------------------------------------------------------\\

    Route::resource('units', 'UnitsController');
    Route::get('get_sub_units_by_base', 'UnitsController@Get_Units_SubBase');
    Route::get('get_units', 'UnitsController@Get_sales_units');

    // ------------------------------- Brands--------------------------\\
    // ------------------------------------------------------------------\\
    Route::resource('brands', 'BrandsController');
    Route::post('brands/delete/by_selection', 'BrandsController@delete_by_selection');

    // ------------------------------- Currencies --------------------------\\
    // ------------------------------------------------------------------\\

    Route::resource('currencies', 'CurrencyController');
    Route::post('currencies/delete/by_selection', 'CurrencyController@delete_by_selection');

    // ------------------------------- WAREHOUSES --------------------------\\

    Route::resource('warehouses', 'WarehouseController');
    Route::post('warehouses/delete/by_selection', 'WarehouseController@delete_by_selection');

    // ------------------------------- WAREHOUSE LOCATIONS (Rack/Location) --------------------------\\
    Route::resource('warehouse_locations', 'WarehouseLocationController');
    Route::get('warehouse_locations/by_warehouse/{id}', 'WarehouseLocationController@by_warehouse');

    // ------------------------------- PRODUCT BATCHES (Pharmacy mode) --------------------------\\
    Route::get('product_batches', 'ProductBatchController@index');
    Route::put('product_batches/{id}', 'ProductBatchController@update');
    Route::post('product_batches/{id}/writeoff', 'ProductBatchController@writeOff');
    Route::delete('product_batches/{id}', 'ProductBatchController@destroy');

    // -------------------------------  Sales --------------------------\\
    // ------------------------------------------------------------------\\

    Route::resource('sales', 'SalesController');
    Route::get('batches_for_sale/{product_id}/{warehouse_id}/{variant_id?}', 'SalesController@batches_for_sale');
    Route::get('get_payments_by_sale/{id}', 'SalesController@Payments_Sale');
    Route::post('sales_send_email', 'SalesController@Send_Email');
    Route::post('sales_send_sms', 'SalesController@Send_SMS');
    Route::post('sales_delete_by_selection', 'SalesController@delete_by_selection');
    Route::get('get_Products_by_sale/{id}', 'SalesController@get_Products_by_sale');

    // ------------------------------- Sales Documents --------------------------\\
    Route::get('sales/{id}/documents', 'SalesController@getDocuments');
    Route::post('sales/{id}/documents', 'SalesController@uploadDocuments');
    Route::get('sales/documents/{id}/download', 'SalesController@downloadDocument');
    Route::delete('sales/documents/{id}', 'SalesController@deleteDocument');
    Route::post('sales_send_whatsapp', 'SalesController@sales_send_whatsapp');
    Route::get('get_today_sales', 'SalesController@get_today_sales');

    // ------------------------------- Payments  Sales --------------------------\\
    // ------------------------------------------------------------------\\

    Route::resource('payment_sale', 'PaymentSalesController');
    Route::get('payment_sale_get_number', 'PaymentSalesController@getNumberOrder');
    Route::post('payment_sale_send_email', 'PaymentSalesController@SendEmail');
    Route::post('payment_sale_send_sms', 'PaymentSalesController@Send_SMS');

    // ------------------------------- Expenses --------------------------\\
    // ------------------------------------------------------------------\\

    Route::resource('expenses', 'ExpensesController');
    Route::post('expenses_delete_by_selection', 'ExpensesController@delete_by_selection');
    // ------------------------------- Expense Documents --------------------------\\
    Route::get('expenses/{id}/documents', 'ExpensesController@getDocuments');
    Route::post('expenses/{id}/documents', 'ExpensesController@uploadDocuments');
    Route::get('expenses/documents/{id}/download', 'ExpensesController@downloadDocument');
    Route::delete('expenses/documents/{id}', 'ExpensesController@deleteDocument');

    // ------------------------------- Expenses Category--------------------------\\
    // ------------------------------------------------------------------\\

    Route::resource('expenses_category', 'CategoryExpenseController');
    Route::post('expenses_category_delete_by_selection', 'CategoryExpenseController@delete_by_selection');

    // ------------------------------- Accounts --------------------------\\
    // ------------------------------------------------------------------\\

    Route::resource('accounts', 'AccountController');
    Route::post('accounts_delete_by_selection', 'AccountController@delete_by_selection');

    // ------------------------------- TransferMoneyController --------------------------\\
    // ------------------------------------------------------------------\\

    Route::resource('transfer_money', 'TransferMoneyController');

    // ------------------------------- Deposits --------------------------\\
    // ------------------------------------------------------------------\\

    Route::resource('deposits', 'DepositsController');
    Route::post('deposits_delete_by_selection', 'DepositsController@delete_by_selection');

    // ------------------------------- deposits Category--------------------------\\
    // ------------------------------------------------------------------\\

    Route::resource('deposits_category', 'CategoryDepositController');
    Route::post('deposits_category_delete_by_selection', 'CategoryDepositController@delete_by_selection');

    // ------------------------------- Sales Return --------------------------\\
    // ------------------------------------------------------------------\\

    Route::resource('returns/sale', 'SalesReturnController');
    Route::post('returns/sale/send/email', 'SalesReturnController@Send_Email');
    Route::post('returns/sale/send/sms', 'SalesReturnController@Send_SMS');
    Route::get('returns/sale/payment/{id}', 'SalesReturnController@Payment_Returns');
    Route::post('returns/sale/delete/by_selection', 'SalesReturnController@delete_by_selection');
    Route::get('returns/sale/create_sell_return/{id}', 'SalesReturnController@create_sell_return');
    Route::get('returns/sale/edit_sell_return/{id}/{sale_id}', 'SalesReturnController@edit_sell_return');

    // ------------------------------- Payment Sale Returns --------------------------\\
    // --------------------------------------------------------------------------------\\

    Route::resource('payment/returns_sale', 'PaymentSaleReturnsController');
    Route::get('payment/returns_sale/Number/order', 'PaymentSaleReturnsController@getNumberOrder');
    Route::post('payment/returns_sale/send/email', 'PaymentSaleReturnsController@SendEmail');
    Route::post('payment/returns_sale/send/sms', 'PaymentSaleReturnsController@Send_SMS');

    // ------------------------------- Damages --------------------------\\
    // ------------------------------------------------------------------\\

    Route::resource('damages', 'DamageController');
    Route::get('damages/detail/{id}', 'DamageController@Damage_detail');
    Route::post('damages/delete/by_selection', 'DamageController@delete_by_selection');
    Route::get('batches_for_damage/{product_id}/{warehouse_id}/{variant_id?}', 'DamageController@batches_for_damage');
    // ------------------------------- Users --------------------------\\
    // ------------------------------------------------------------------\\

    Route::get('get_user_auth', 'UserController@GetUserAuth');
    Route::get('users_list_for_select', 'UserController@listForSelect');
    Route::resource('users', 'UserController');
    Route::put('users_switch_activated/{id}', 'UserController@IsActivated');
    Route::get('Get_user_profile', 'UserController@GetInfoProfile');
    Route::put('update_user_profile/{id}', 'UserController@updateProfile');

    // ------------------------------- Permission Groups user -----------\\
    // ------------------------------------------------------------------\\

    Route::get('roles/check/create_page', 'PermissionsController@Check_Create_Page');
    Route::resource('roles', 'PermissionsController');
    Route::post('roles/delete/by_selection', 'PermissionsController@delete_by_selection');

    // ------------------------------- Settings ------------------------\\
    // ------------------------------------------------------------------\\
    Route::get('settings/dark-mode', 'SettingsController@getDarkMode');
    Route::put('settings/dark-mode', 'SettingsController@updateDarkMode');
    Route::resource('settings', 'SettingsController');
    Route::get('get_Settings_data_api', 'SettingsController@get_Settings_data_api');
    Route::get('get_Settings_data', 'SettingsController@getSettings');
    Route::put('settings/dashboard-grid-layout', 'SettingsController@updateDashboardGridLayout');
    // Dedicated Dark Mode endpoints (independent from other settings APIs)
    Route::put('pos_settings/{id}', 'SettingsController@update_pos_settings');
    Route::get('get_pos_Settings', 'SettingsController@get_pos_Settings');
    Route::get('get_pos_Settings_api', 'SettingsController@get_pos_Settings_api');

    // ------------------------------- Security Settings (additive) ------------------------\\
    // Active login sessions (Passport tokens) + logout endpoints
    Route::get('security/sessions', 'SecuritySettingsController@sessions');
    Route::delete('security/sessions/{tokenId}', 'SecuritySettingsController@logoutSession');
    Route::post('security/sessions/logout-other', 'SecuritySettingsController@logoutAllOtherDevices');
    Route::get('security/login-activity-report', 'SecuritySettingsController@loginActivityReport');

    // ------------------------------- appearance_settings ------------------------\\
    // ------------------------------------------------------------------\\

    Route::get('get_appearance_settings', 'SettingsController@get_appearance_settings');
    Route::put('update_appearance_settings/{id}', 'SettingsController@update_appearance_settings');

    // ------------------------------- Profile Password ------------------------\\
    Route::post('update_user_password', 'UserController@updatePassword');

    // ------------------------------- Mail Settings ------------------------\\

    Route::put('update_config_mail/{id}', 'MailSettingsController@update_config_mail');
    Route::get('get_config_mail', 'MailSettingsController@get_config_mail');
    Route::post('test_config_mail', 'MailSettingsController@test_config_mail');

    // ------------------------------- SMS Settings ------------------------\\

    Route::get('get_sms_config', 'Sms_SettingsController@get_sms_config');
    Route::get('get_sms_config_ws', 'Sms_SettingsController@get_sms_config_ws');
    Route::post('update_twilio_config', 'Sms_SettingsController@update_twilio_config');
    Route::post('update_nexmo_config', 'Sms_SettingsController@update_nexmo_config');
    Route::post('update_infobip_config', 'Sms_SettingsController@update_infobip_config');
    Route::post('update_termi_config', 'Sms_SettingsController@update_termi_config');
    Route::post('update_custom_config', 'Sms_SettingsController@update_custom_config');

    Route::put('update_Default_SMS', 'Sms_SettingsController@update_Default_SMS');

    // notifications_template
    Route::get('get_sms_template', 'Notifications_Template@get_sms_template');
    Route::put('update_sms_body', 'Notifications_Template@update_sms_body');

    Route::get('get_emails_template', 'Notifications_Template@get_emails_template');
    Route::put('update_custom_email', 'Notifications_Template@update_custom_email');

    // ------------------------------- Backup --------------------------\\
    // ------------------------------------------------------------------\\

    Route::get('get_backup', 'BackupController@Get_Backup');
    Route::get('generate_new_backup', 'BackupController@Generate_Backup');
    Route::delete('delete_backup/{name}', 'BackupController@Delete_Backup');

    // ------------------------------- System Health --------------------------\\
    Route::get('system_health', 'SystemHealthController@index');
    Route::get('system_health/pdf', 'SystemHealthController@pdf');

});

// NEW FEATURE - SAFE ADDITION: Accounting V2 (isolated routes)
Route::middleware(['auth:api', 'Is_Active', 'request.safety'])->group(function () {
    Route::prefix('accounting/v2')->group(function () {
        // Dashboard
        Route::get('dashboard', 'AccountingV2\\DashboardController@summary');
        // Chart of Accounts
        Route::get('coa', 'AccountingV2\\ChartOfAccountsController@index');
        Route::post('coa', 'AccountingV2\\ChartOfAccountsController@store');
        Route::put('coa/{id}', 'AccountingV2\\ChartOfAccountsController@update');
        Route::delete('coa/{id}', 'AccountingV2\\ChartOfAccountsController@destroy');

        // Journal Entries
        Route::get('journal-entries', 'AccountingV2\\JournalEntriesController@index');
        Route::get('journal-entries/{id}', 'AccountingV2\\JournalEntriesController@show');
        Route::post('journal-entries', 'AccountingV2\\JournalEntriesController@store');
        Route::post('journal-entries/{id}/post', 'AccountingV2\\JournalEntriesController@post');
        Route::put('journal-entries/{id}', 'AccountingV2\\JournalEntriesController@update');
        Route::patch('journal-entries/{id}', 'AccountingV2\\JournalEntriesController@update');
        Route::delete('journal-entries/{id}', 'AccountingV2\\JournalEntriesController@destroy');

        // Reports
        Route::get('reports/trial-balance', 'AccountingV2\\ReportsController@trialBalance');
        Route::get('reports/profit-loss', 'AccountingV2\\ReportsController@profitAndLoss');
        Route::get('reports/balance-sheet', 'AccountingV2\\ReportsController@balanceSheet');
        Route::get('reports/tax-summary', 'AccountingV2\\ReportsController@taxSummary');
    });
});

// -------------------------------  Print & PDF ------------------------\\
// ------------------------------------------------------------------\\

Route::get('sale_pdf/{id}', 'SalesController@Sale_PDF');
Route::get('sale_print_html/{id}', 'SalesController@Sale_PDF_Inline');
Route::get('booking_pdf/{id}', 'BookingController@booking_pdf');
Route::get('service_job_pdf/{id}', 'ServiceJobController@service_job_pdf');
Route::get('service_quote_pdf/{id}', 'ServiceJobController@service_quote_pdf');
Route::get('return_sale_pdf/{id}', 'SalesReturnController@Return_pdf');
Route::get('payment_return_sale_pdf/{id}', 'PaymentSaleReturnsController@payment_return');
Route::get('payment_sale_pdf/{id}', 'PaymentSalesController@payment_sale');
Route::get('sales_print_invoice/{id}', 'SalesController@Print_Invoice_POS');
Route::post('direct_network_print/{id}', 'SalesController@Direct_Network_Print_POS');
Route::get('transfer_pdf/{id}', 'TransferController@transfer_pdf');
Route::get('damage_pdf/{id}', 'DamageController@damage_pdf');

// Route::get('/available-modules', 'ModuleSettingsController@get_modules_enabled');

