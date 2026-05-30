import Vue from "vue";
import store from "./store";
import NProgress from "nprogress";
import Router from "vue-router";
Vue.use(Router);






// create new router

const baseRoutes = [
    {
        path: "/",
        component: () => import("./views/app"),
        redirect: "/app/dashboard",
        name: 'app',

        children: [
            {
                path: "/app/dashboard",
                name: "dashboard",
                component: () =>
                    import(
                        /* webpackChunkName: "dashboard" */ "./views/app/dashboard/dashboard"
                    )
            },
            // User Management
            {
                path: "/app/User_Management",
                redirect: "/app/User_Management/Users",
                component: () =>
                    import(
                        /* webpackChunkName: "users" */ "./views/app/pages/people"
                    ),
                children: [
                    {
                        name: "Users",
                        path: "Users",
                        component: () =>
                            import(
                                /* webpackChunkName: "users" */ "./views/app/pages/people/users"
                            )
                    },
                    {
                        name: "Create_User",
                        path: "Users/store",
                        component: () =>
                            import(
                                /* webpackChunkName: "create_user" */ "./views/app/pages/people/CreateUser"
                            )
                    },
                    {
                        name: "Edit_User",
                        path: "Users/edit/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "edit_user" */ "./views/app/pages/people/EditUser"
                            )
                    },
                    {
                        name: "Permissions",
                        path: "permissions",
                        component: () =>
                            import(
                                /* webpackChunkName: "permissions" */ "./views/app/pages/settings/permissions/Permissions"
                            )
                    }
                ]
            },
            // People
            {
                path: "/app/People",
                redirect: "/app/People/Customers",
                component: () =>
                    import(
                        /* webpackChunkName: "people" */ "./views/app/pages/people"
                    ),
                children: [
                    {
                        name: "Customers",
                        path: "Customers",
                        component: () =>
                            import(
                                /* webpackChunkName: "customers" */ "./views/app/pages/people/customers"
                            )
                    },
                    {
                        name: "Create_Customer",
                        path: "Customers/create",
                        component: () =>
                            import(
                                /* webpackChunkName: "create_customer" */ "./views/app/pages/people/CreateCustomer"
                            )
                    },
                    {
                        name: "Edit_Customer",
                        path: "Customers/edit/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "edit_customer" */ "./views/app/pages/people/EditCustomer"
                            )
                    },
                    {
                        name: "CustomerDetails",
                        path: "Customers/details/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "customer_details" */ "./views/app/pages/people/CustomerDetails"
                            )
                    },
                    {
                        name: "CustomerLedger",
                        path: "Customers/ledger/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "customer_ledger" */ "./views/app/pages/people/CustomerLedger"
                            )
                    },
                    {
                        name: "Import_Customers",
                        path: "Customers_import",
                        component: () =>
                            import(
                                /* webpackChunkName: "import_customers" */ "./views/app/pages/people/ImportCustomers"
                            )
                    },
                    {
                        name: "Suppliers",
                        path: "Suppliers",
                        component: () =>
                            import(
                                /* webpackChunkName: "suppliers" */ "./views/app/pages/people/providers"
                            )
                    },
                    {
                        name: "Create_Supplier",
                        path: "Suppliers/create",
                        component: () =>
                            import(
                                /* webpackChunkName: "create_supplier" */ "./views/app/pages/people/CreateSupplier"
                            )
                    },
                    {
                        name: "Edit_Supplier",
                        path: "Suppliers/edit/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "edit_supplier" */ "./views/app/pages/people/EditSupplier"
                            )
                    },
                    {
                        name: "Import_Suppliers",
                        path: "Suppliers_import",
                        component: () =>
                            import(
                                /* webpackChunkName: "import_suppliers" */ "./views/app/pages/people/ImportSuppliers"
                            )
                    }
                ]
            },
            //Products
            {
                path: "/app/products",
                component: () =>
                    import(
                        /* webpackChunkName: "products" */ "./views/app/pages/products"
                    ),
                redirect: "app/products/list",
                children: [
                    {
                        name: "index_products",
                        path: "list",
                        component: () =>
                            import(
                                /* webpackChunkName: "index_products" */ "./views/app/pages/products/index_products"
                            )
                    },
                    {
                    path: "import",
                    name: "import_products",
                    component: () =>
                        import(/* webpackChunkName: "import_products" */ "./views/app/pages/products/Import_products.vue"),
                    },
                    {
                        path: "import-update",
                        name: "import_products_update",
                        component: () =>
                            import(/* webpackChunkName: "import_products_update" */ "./views/app/pages/products/Import_products_update.vue"),
                        },
                    {
                        path: "store",
                        name: "store_product",
                        component: () =>
                            import(
                                /* webpackChunkName: "store_product" */ "./views/app/pages/products/Add_product"
                            )
                    },
                    {
                        path: "edit/:id",
                        name: "edit_product",
                        component: () =>
                            import(
                                /* webpackChunkName: "edit_product" */ "./views/app/pages/products/Edit_product"
                            )
                    },
                    {
                        path: "detail/:id",
                        name: "detail_product",
                        component: () =>
                            import(
                                /* webpackChunkName: "detail_product" */ "./views/app/pages/products/Detail_Product"
                            )
                    },

                    {
                        path: "opening_stock_import",
                        name: "opening_stock_import",
                        component: () =>
                            import(
                                /* webpackChunkName: "opening_stock_import" */ "./views/app/pages/products/opening_stock_import"
                            )
                    },

                    {
                        path: "barcode",
                        name: "barcode",
                        component: () =>
                            import(
                                /* webpackChunkName: "barcode" */ "./views/app/pages/products/barcode"
                            )
                    },

                    {
                        path: "count_stock",
                        name: "count_stock",
                        component: () =>
                            import(
                                /* webpackChunkName: "count_stock" */ "./views/app/pages/products/count_stock"
                            )
                    },
                     // categories
                     {
                        name: "categories",
                        path: "Categories",
                        component: () =>
                            import(
                                /* webpackChunkName: "Categories" */ "./views/app/pages/products/categorie"
                            )
                    },

                    // brands
                    {
                        name: "brands",
                        path: "Brands",
                        component: () =>
                            import(
                                /* webpackChunkName: "Brands" */ "./views/app/pages/products/brands"
                            )
                    },

                    // units
                    {
                        name: "units",
                        path: "Units",
                        component: () =>
                            import(
                                /* webpackChunkName: "units" */ "./views/app/pages/products/units"
                            )
                    },

                    // batches (pharmacy-mode batch & expiry tracking)
                    {
                        name: "batches",
                        path: "Batches",
                        component: () =>
                            import(
                                /* webpackChunkName: "batches" */ "./views/app/pages/products/batches"
                            )
                    },
                ]
            },

            // Damages
            {
                path: "/app/damages",
                component: () =>
                    import(
                        /* webpackChunkName: "damages" */ "./views/app/pages/damage"
                    ),
                redirect: "/app/damages/list",
                children: [
                    {
                        name: "index_damage",
                        path: "list",
                        component: () =>
                            import(
                                /* webpackChunkName: "index_damage" */
                                "./views/app/pages/damage/index_Damage"
                            )
                    },
                    {
                        name: "store_damage",
                        path: "store",
                        component: () =>
                            import(
                                /* webpackChunkName: "store_damage" */
                                "./views/app/pages/damage/Create_Damage"
                            )
                    },
                    {
                        name: "edit_damage",
                        path: "edit/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "edit_damage" */
                                "./views/app/pages/damage/Edit_Damage"
                            )
                    }
                ]
            },

            // accounts
             {
                name: "accounts",
                path: "/app/accounts",
                component: () =>
                    import(
                        /* webpackChunkName: "accounts" */ "./views/app/pages/accounts/account_list"
                    )
            },

            // Service & Maintenance
            {
                path: "/app/service",
                component: () =>
                    import(
                        /* webpackChunkName: "service" */ "./views/app/pages/service"
                    ),
                redirect: "/app/service/jobs",
                children: [
                    {
                        name: "service_jobs_index",
                        path: "jobs",
                        component: () =>
                            import(
                                /* webpackChunkName: "service_jobs_index" */ "./views/app/pages/service/ServiceJobsList"
                            )
                    },
                    {
                        name: "service_job_create",
                        path: "jobs/create",
                        component: () =>
                            import(
                                /* webpackChunkName: "service_job_create" */ "./views/app/pages/service/ServiceJobForm"
                            )
                    },
                    {
                        name: "service_job_edit",
                        path: "jobs/edit/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "service_job_edit" */ "./views/app/pages/service/ServiceJobForm"
                            )
                    },
                    {
                        name: "service_job_details",
                        path: "jobs/details/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "service_job_details" */ "./views/app/pages/service/ServiceJobDetails"
                            )
                    },
                    {
                        name: "service_checklist_categories",
                        path: "checklist-categories",
                        component: () =>
                            import(
                                /* webpackChunkName: "service_checklist_categories" */ "./views/app/pages/service/ServiceChecklistCategories"
                            )
                    },
                    {
                        name: "service_checklists",
                        path: "checklists",
                        component: () =>
                            import(
                                /* webpackChunkName: "service_checklists" */ "./views/app/pages/service/ServiceChecklists"
                            )
                    },
                    {
                        name: "service_technicians",
                        path: "technicians",
                        component: () =>
                            import(
                                /* webpackChunkName: "service_technicians" */ "./views/app/pages/service/ServiceTechnicians"
                            )
                    },
                    {
                        name: "customer_maintenance_history",
                        path: "history",
                        component: () =>
                            import(
                                /* webpackChunkName: "customer_maintenance_history" */ "./views/app/pages/service/CustomerMaintenanceHistory"
                            )
                    }
                ]
            },

             // transfer_money
             {
                name: "transfer_money",
                path: "/app/transfer_money",
                component: () =>
                    import(
                        /* webpackChunkName: "transfer_money" */ "./views/app/pages/accounts/transfer_money"
                    )
            },

          

            //expenses
            {
                path: "/app/expenses",
                component: () =>
                    import(
                        /* webpackChunkName: "expenses" */ "./views/app/pages/expense"
                    ),
                redirect: "/app/expenses/list",
                children: [
                    {
                        name: "index_expense",
                        path: "list",
                        component: () =>
                            import(
                                /* webpackChunkName: "index_expense" */ "./views/app/pages/expense/index_expense"
                            )
                    },
                    {
                        name: "store_expense",
                        path: "store",
                        component: () =>
                            import(
                                /* webpackChunkName: "store_expense" */ "./views/app/pages/expense/create_expense"
                            )
                    },
                    {
                        name: "edit_expense",
                        path: "edit/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "edit_expense" */ "./views/app/pages/expense/edit_expense"
                            )
                    },
                    {
                        name: "expense_category",
                        path: "category",
                        component: () =>
                            import(
                                /* webpackChunkName: "expense_category" */ "./views/app/pages/expense/category_expense"
                            )
                    },

                ]
            },

            //deposits
            {
                path: "/app/deposits",
                component: () =>
                    import(
                        /* webpackChunkName: "deposits" */ "./views/app/pages/deposits"
                    ),
                redirect: "/app/deposits/list",
                children: [

                    {
                        name: "index_deposit",
                        path: "list",
                        component: () =>
                            import(
                                /* webpackChunkName: "index_deposit" */ "./views/app/pages/deposits/index_deposit"
                            )
                    },
                    {
                        name: "store_deposit",
                        path: "store",
                        component: () =>
                            import(
                                /* webpackChunkName: "store_deposit" */ "./views/app/pages/deposits/create_deposit"
                            )
                    },
                    {
                        name: "edit_deposit",
                        path: "edit/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "edit_deposit" */ "./views/app/pages/deposits/edit_deposit"
                            )
                    },
                    {
                        name: "deposit_category",
                        path: "category",
                        component: () =>
                            import(
                                /* webpackChunkName: "deposit_category" */ "./views/app/pages/deposits/deposit_category"
                            )
                    }
                ]
            },

            // Advanced Accounting (NEW FEATURE - SAFE ADDITION)
            {
                path: "/app/accounting-v2",
                component: () =>
                    import(
                        /* webpackChunkName: "accounting_v2" */ "./views/app/pages/accounting_v2"
                    ),
                redirect: "/app/accounting-v2/dashboard",
                children: [
                    {
                        name: "accounting_v2_dashboard",
                        path: "dashboard",
                        component: () =>
                            import(
                                /* webpackChunkName: "accounting_v2_dashboard" */ "./views/app/pages/accounting_v2/dashboard"
                            )
                    },
                    {
                        name: "accounting_v2_chart_of_accounts",
                        path: "chart-of-accounts",
                        component: () =>
                            import(
                                /* webpackChunkName: "accounting_v2_chart_of_accounts" */ "./views/app/pages/accounting_v2/chart_of_accounts"
                            )
                    },
                    {
                        name: "accounting_v2_journal_entries",
                        path: "journal-entries",
                        component: () =>
                            import(
                                /* webpackChunkName: "accounting_v2_journal_entries" */ "./views/app/pages/accounting_v2/journal_entries"
                            )
                    },
                    {
                        name: "accounting_v2_trial_balance",
                        path: "reports/trial-balance",
                        component: () =>
                            import(
                                /* webpackChunkName: "accounting_v2_trial_balance" */ "./views/app/pages/accounting_v2/reports/trial_balance"
                            )
                    },
                    {
                        name: "accounting_v2_profit_loss",
                        path: "reports/profit-and-loss",
                        component: () =>
                            import(
                                /* webpackChunkName: "accounting_v2_profit_loss" */ "./views/app/pages/accounting_v2/reports/profit_and_loss"
                            )
                    },
                    {
                        name: "accounting_v2_balance_sheet",
                        path: "reports/balance-sheet",
                        component: () =>
                            import(
                                /* webpackChunkName: "accounting_v2_balance_sheet" */ "./views/app/pages/accounting_v2/reports/balance_sheet"
                            )
                    },
                    {
                        name: "accounting_v2_tax_report",
                        path: "reports/tax-report",
                        component: () =>
                            import(
                                /* webpackChunkName: "accounting_v2_tax_report" */ "./views/app/pages/accounting_v2/reports/tax_report"
                            )
                    }
                ]
            },
            //Sale
            {
                path: "/app/sales",
                component: () =>
                    import(
                        /* webpackChunkName: "sales" */ "./views/app/pages/sales"
                    ),
                redirect: "/app/sales/list",
                children: [
                    {
                        name: "index_sales",
                        path: "list",
                        component: () =>
                            import(
                                /* webpackChunkName: "index_sales" */ "./views/app/pages/sales/index_sale"
                            )
                    },
                    {
                        name: "edit_sale",
                        path: "edit/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "edit_sale" */ "./views/app/pages/sales/edit_sale"
                            )
                    },
                    {
                        name: "sale_return",
                        path: "sale_return/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "create_sale_return" */ "./views/app/pages/sale_return/create_sale_return"
                            )
                    },
                    {
                        name: "detail_sale",
                        path: "detail/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "detail_sale" */ "./views/app/pages/sales/detail_sale"
                            )
                    },
                ]
            },

            // Sales Return
            {
                path: "/app/sale_return",
                component: () =>
                    import(
                        /* webpackChunkName: "sale_return" */ "./views/app/pages/sale_return"
                    ),
                redirect: "/app/sale_return/list",
                children: [
                    {
                        name: "index_sale_return",
                        path: "list",
                        component: () =>
                            import(
                                /* webpackChunkName: "index_sale_return" */
                                "./views/app/pages/sale_return/index_sale_return"
                            )
                    },
                    {
                        name: "edit_sale_return",
                        path: "edit/:id/:sale_id",
                        component: () =>
                            import(
                                /* webpackChunkName: "edit_sale_return" */
                                "./views/app/pages/sale_return/edit_sale_return"
                            )
                    },
                    {
                        name: "detail_sale_return",
                        path: "detail/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "detail_sale_return" */
                                "./views/app/pages/sale_return/detail_sale_return"
                            )
                    }
                ]
            },

            // Settings
            {
                path: "/app/settings",
                component: () =>
                    import(
                        /* webpackChunkName: "settings" */ "./views/app/pages/settings"
                    ),
                redirect: "/app/settings/System_settings",
                children: [
                    // payment_methods
                    {
                        name: "payment_methods",
                        path: "payment_methods",
                        component: () =>
                            import(
                                /* webpackChunkName: "payment_methods" */ "./views/app/pages/settings/payment_methods"
                            )
                    },

                     // sms_settings
                     {
                        name: "sms_settings",
                        path: "sms_settings",
                        component: () =>
                            import(
                                /* webpackChunkName: "sms_settings" */ "./views/app/pages/settings/sms_settings"
                            )
                    },
                     // sms_templates
                     {
                        name: "sms_templates",
                        path: "sms_templates",
                        component: () =>
                            import(
                                /* webpackChunkName: "sms_templates" */ "./views/app/pages/settings/sms_templates"
                            )
                    },

                     // email_templates
                     {
                        name: "email_templates",
                        path: "email_templates",
                        component: () =>
                            import(
                                /* webpackChunkName: "email_templates" */ "./views/app/pages/settings/email_templates"
                            )
                    },

                    // appearance_settings
                    {
                    name: "appearance_settings",
                    path: "appearance_settings",
                    component: () =>
                        import(
                            /* webpackChunkName: "appearance_settings" */ "./views/app/pages/settings/appearance_settings"
                        )
                    },

                    // translations_settings
                    {
                    name: "translations_settings",
                    path: "translations_settings",
                    component: () =>
                        import(
                            /* webpackChunkName: "translations_settings" */ "./views/app/pages/settings/translations_settings"
                        )
                    },

                    {
                    name: "translations_view",
                    path: "/translations_view/:locale",
                    component: () =>
                        import(
                        /* webpackChunkName: "translations_view" */ "./views/app/pages/settings/translations_view"
                        )
                    },

                    // pos_settings (POS behaviour & display settings)
                    {
                      name: "pos_settings",
                      path: "pos_settings",
                      component: () =>
                        import(
                          /* webpackChunkName: "pos_settings" */ "./views/app/pages/settings/pos_settings"
                        )
                    },

                    // pos_receipt (POS receipt layout & print configuration)
                    {
                      name: "pos_receipt",
                      path: "pos_receipt",
                      component: () =>
                        import(
                          /* webpackChunkName: "pos_receipt" */ "./views/app/pages/settings/pos_receipt"
                        )
                    },
                        // mail_settings
                     {
                        name: "mail_settings",
                        path: "mail_settings",
                        component: () =>
                            import(
                                /* webpackChunkName: "mail_settings" */ "./views/app/pages/settings/mail_settings"
                            )
                        },
                    // currencies
                    {
                        name: "currencies",
                        path: "Currencies",
                        component: () =>
                            import(
                                /* webpackChunkName: "Currencies" */ "./views/app/pages/settings/currencies"
                            )
                    },

                    // Backup
                    {
                        name: "Backup",
                        path: "Backup",
                        component: () =>
                            import(
                                /* webpackChunkName: "Backup" */ "./views/app/pages/settings/backup"
                            )
                    },

                    // System Health Dashboard
                    {
                        name: "system_health",
                        path: "system_health",
                        component: () =>
                            import(
                                /* webpackChunkName: "system_health" */ "./views/app/pages/settings/system_health"
                            )
                    },

                    // Warehouses
                    {
                        name: "Warehouses",
                        path: "Warehouses",
                        component: () =>
                            import(
                                /* webpackChunkName: "Warehouses" */ "./views/app/pages/settings/warehouses"
                            )
                    },
                    // System Settings
                    {
                        name: "system_settings",
                        path: "System_settings",
                        component: () =>
                            import(
                                /* webpackChunkName: "System_settings" */ "./views/app/pages/settings/system_settings"
                            )
                    },

                    // Login Device Management (Security Sessions)
                    {
                        name: "login_devices",
                        path: "login_devices",
                        component: () =>
                            import(
                                /* webpackChunkName: "login_devices" */ "./views/app/pages/settings/login_devices"
                            )
                    },

                    // Custom Fields
                    {
                        name: "custom_fields",
                        path: "custom_fields",
                        component: () =>
                            import(
                                /* webpackChunkName: "custom_fields" */ "./views/app/pages/settings/custom_fields"
                            )
                    },
                ]
            },
            // Reports
            {
                path: "/app/reports",
                component: () => import("./views/app/pages/reports"),
                redirect: "/app/reports/profit_and_loss",
                children: [
                    {
                        name: "payments_sales",
                        path: "payments_sale",
                        component: () =>
                            import(
                                /* webpackChunkName: "payments_sales" */
                                "./views/app/pages/reports/payments/payments_sales"
                            )
                    },
                    {
                        name: "payments_sales_returns",
                        path: "payments_sales_returns",
                        component: () =>
                            import(
                                /* webpackChunkName: "payments_sales_returns" */
                                "./views/app/pages/reports/payments/payments_sales_returns"
                            )
                    },

                     {
                        name: "inactive_customers",
                        path: "inactive_customers",
                        component: () =>
                            import(
                                /* webpackChunkName: "Inactive_Customers_Report" */
                                "./views/app/pages/reports/Inactive_Customers_Report"
                            )
                    },

                     {
                        name: "zero_sales_products_report",
                        path: "zero_sales_products_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "Zero_Sales_Products_Report" */
                                "./views/app/pages/reports/Zero_Sales_Products_Report"
                            )
                    },

                      {
                        name: "dead_stock_report",
                        path: "dead_stock_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "Dead_Stock_Report" */
                                "./views/app/pages/reports/Dead_Stock_Report"
                            )
                    },

                    {
                        name: "expiry_report",
                        path: "expiry_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "Expiry_Report" */
                                "./views/app/pages/reports/Expiry_Report"
                            )
                    },

                    {
                        name: "batch_register_report",
                        path: "batch_register_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "Batch_Register_Report" */
                                "./views/app/pages/reports/Batch_Register_Report"
                            )
                    },
                    {
                        name: "batch_history_report",
                        path: "batch_history_report/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "Batch_History_Report" */
                                "./views/app/pages/reports/Batch_History_Report"
                            )
                    },

                    {
                        name: "stock_aging_report",
                        path: "stock_aging_report",
                        component: () =>
                            import(
                            /* webpackChunkName: "Stock_Aging_Report" */
                            "./views/app/pages/reports/Stock_Aging_Report"
                            )
                    },


                    

                      {
                        name: "draft_invoices_report",
                        path: "draft_invoices_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "Draft_Invoices_Report" */
                                "./views/app/pages/reports/Draft_Invoices_Report"
                            )
                    },


                    {
                        name: "discount_summary_report",
                        path: "discount_summary_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "Discount_Summary_Report" */
                                "./views/app/pages/reports/Discount_Summary_Report"
                            )
                    },

                    {
                        name: "customer_loyalty_points_report",
                        path: "customer_loyalty_points_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "Customer_Loyalty_Points_Report" */
                                "./views/app/pages/reports/Customer_Loyalty_Points_Report"
                            )
                    },

                     {
                        name: "tax_summary_report",
                        path: "tax_summary_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "Tax_Summary_Report" */
                                "./views/app/pages/reports/Tax_Summary_Report"
                            )
                    },

                    {
                        name: "cash_flow_report",
                        path: "cash_flow_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "cash_flow_report" */
                                "./views/app/pages/reports/Cash_Flow_Report"
                            )
                    },

                    {
                        name: "report_transactions",
                        path: "report_transactions",
                        component: () =>
                            import(
                                /* webpackChunkName: "report_transactions" */
                                "./views/app/pages/reports/report_transactions"
                            )
                    },

                     {
                        name: "seller_report",
                        path: "seller_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "seller_report" */
                                "./views/app/pages/reports/seller_report"
                            )
                    },

                     {
                        name: "report_sales_by_category",
                        path: "report_sales_by_category",
                        component: () =>
                            import(
                                /* webpackChunkName: "report_sales_by_category" */
                                "./views/app/pages/reports/report_sales_by_category"
                            )
                    },

                      {
                        name: "report_sales_by_brand",
                        path: "report_sales_by_brand",
                        component: () =>
                            import(
                                /* webpackChunkName: "report_sales_by_brand" */
                                "./views/app/pages/reports/report_sales_by_brand"
                            )
                    },

                    

                    {
                        name: "profit_and_loss",
                        path: "profit_and_loss",
                        component: () =>
                            import(
                                /* webpackChunkName: "profit_and_loss" */
                                "./views/app/pages/reports/profit_and_loss"
                            )
                    },

                    {
                        name: "inventory_valuation_summary",
                        path: "inventory_valuation_summary",
                        component: () =>
                            import(
                                /* webpackChunkName: "inventory_valuation_summary" */
                                "./views/app/pages/reports/inventory_valuation_summary"
                            )
                    },

                    {
                        name: "stock_inventory_valuation",
                        path: "stock_inventory_valuation",
                        component: () =>
                            import(
                                /* webpackChunkName: "stock_inventory_valuation" */
                                "./views/app/pages/reports/stock_inventory_valuation"
                            )
                    },

                    {
                        name: "analytics_report",
                        path: "analytics_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "analytics_report" */
                                "./views/app/pages/reports/Analytics_Report"
                            )
                    },


                    {
                        name: "expenses_report",
                        path: "expenses_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "expenses_report" */
                                "./views/app/pages/reports/expenses_report"
                            )
                    },

                    {
                        name: "deposits_report",
                        path: "deposits_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "deposits_report" */
                                "./views/app/pages/reports/deposits_report"
                            )
                    },

                    {
                        name: "return_ratio_report",
                        path: "return_ratio_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "return_ratio_report" */
                                "./views/app/pages/reports/Return_Ratio_Report"
                            )
                    },

                    {
                        name: "negative_stock_report",
                        path: "negative_stock_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "negative_stock_report" */
                                "./views/app/pages/reports/Negative_Stock_Report"
                            )
                    },


                    {
                        name: "quantity_alerts",
                        path: "quantity_alerts",
                        component: () =>
                            import(
                                /* webpackChunkName: "quantity_alerts" */
                                "./views/app/pages/reports/quantity_alerts"
                            )
                    },
                    {
                        name: "warehouse_report",
                        path: "warehouse_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "warehouse_report" */
                                "./views/app/pages/reports/warehouse_report"
                            )
                    },

                    {
                        name: "internal_location_report",
                        path: "internal_location_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "internal_location_report" */
                                "./views/app/pages/reports/internal_location_report"
                            )
                    },

                    {
                        name: "sales_report",
                        path: "sales_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "sales_report" */
                                "./views/app/pages/reports/sales_report"
                            )
                    },

                    {
                        name: "product_sales_report",
                        path: "product_sales_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "product_sales_report" */
                                "./views/app/pages/reports/product_sales_report"
                            )
                    },

                    {
                        name: "customers_report",
                        path: "customers_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "customers_report" */
                                "./views/app/pages/reports/customers_report"
                            )
                    },
                    {
                        name: "detail_customer_report",
                        path: "detail_customer/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "detail_customer_report" */
                                "./views/app/pages/reports/detail_Customer_Report"
                            )
                    },

                    {
                        name: "top_selling_products",
                        path: "top_selling_products",
                        component: () =>
                            import(
                                /* webpackChunkName: "top_selling_products" */
                                "./views/app/pages/reports/top_selling_products"
                            )
                    },

                    {
                        name: "product_report",
                        path: "product_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "product_report" */
                                "./views/app/pages/reports/product_report"
                            )
                    },
                    {
                        name: "detail_product_report",
                        path: "detail_product/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "detail_product_report" */
                                "./views/app/pages/reports/detail_product_report"
                            )
                    },

                    {
                        name: "top_customers",
                        path: "top_customers",
                        component: () =>
                            import(
                                /* webpackChunkName: "top_customers" */
                                "./views/app/pages/reports/top_customers"
                            )
                    },

                    {
                        name: "stock_report",
                        path: "stock_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "stock_report" */
                                "./views/app/pages/reports/stock_report"
                            )
                    },
                    {
                        name: "detail_stock_report",
                        path: "detail_stock/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "detail_stock_report" */
                                "./views/app/pages/reports/detail_stock_report"
                            )
                    },

                    {
                        name: "users_report",
                        path: "users_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "users_report" */
                                "./views/app/pages/reports/users_report"
                            )
                    },
                    {
                        name: "detail_user_report",
                        path: "detail_user/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "detail_user_report" */
                                "./views/app/pages/reports/detail_user_report"
                            )
                    },
                    {
                        name: "login_activity_report",
                        path: "login_activity_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "login_activity_report" */
                                "./views/app/pages/reports/login_activity_report"
                            )
                    },

                    {
                        name: "report_error_logs",
                        path: "report_error_logs",
                        component: () =>
                            import(
                                /* webpackChunkName: "report_error_logs" */
                                "./views/app/pages/reports/report_error_logs"
                            )
                    },
                     // Cash Register Report
                    {
                        name: "cash_register_report",
                        path: "cash-registers",
                        component: () =>
                            import(/* webpackChunkName: "cash_register_report" */ "./views/app/pages/reports/Cash_Register_Report")
                    },
                    {
                        name: "warranty_guarantee_report",
                        path: "warranty_guarantee_report",
                        component: () =>
                            import(/* webpackChunkName: "warranty_guarantee_report" */ "./views/app/pages/reports/Warranty_Guarantee_Report")
                    },
                    {
                        name: "attendance_report",
                        path: "attendance_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "Attendance_Report" */
                                "./views/app/pages/reports/Attendance_Report"
                            )
                    },
                    {
                        name: "service_jobs_report",
                        path: "service_jobs_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "Service_Jobs_Report" */
                                "./views/app/pages/reports/Service_Jobs_Report"
                            )
                    },
                    {
                        name: "checklist_completion_report",
                        path: "checklist_completion_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "Checklist_Completion_Report" */
                                "./views/app/pages/reports/Checklist_Completion_Report"
                            )
                    },
                    {
                        name: "customer_maintenance_history_report",
                        path: "customer_maintenance_history_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "Customer_Maintenance_History_Report" */
                                "./views/app/pages/reports/Customer_Maintenance_History_Report"
                            )
                    },
                ]
            },

            {
                name: "profile",
                path: "/app/profile",
                component: () =>
                    import(
                        /* webpackChunkName: "profile" */ "./views/app/pages/profile"
                    )
            }
        ]
    },

    {
        name: "pos",
        path: "/app/pos",
        // beforeEnter: authenticate,
        component: () =>
            import(/* webpackChunkName: "pos" */ "./views/app/pages/pos")
    },





    {
        path: "*",
        name: "NotFound",
        component: () =>
            import(
                /* webpackChunkName: "NotFound" */ "./views/app/pages/notFound"
            )
    },

    {
        path: "not_authorize",
        name: "not_authorize",
        component: () =>
            import(
                /* webpackChunkName: "not_authorize" */ "./views/app/pages/NotAuthorize"
            )
    }
];

const router = new Router({
    mode: "history",
    linkActiveClass: "open",
    routes: baseRoutes,
    scrollBehavior(to, from, savedPosition) {
        return { x: 0, y: 0 };
    }
});

// Fix redundant navigation error
const originalPush = Router.prototype.push;
Router.prototype.push = function push(location, onResolve, onReject) {
  if (onResolve || onReject)
    return originalPush.call(this, location, onResolve, onReject);
  return originalPush.call(this, location).catch(err => err);
};

// ✅ Export function to set up navigation guards
export function setupRouterGuards(i18n) {
  router.beforeEach(async (to, from, next) => {
    if (to.path) {
      NProgress.start();
      NProgress.set(0.1);
    }

    // Ensure we read the actual language string from the language module
    let savedLang = store.getters.getLanguage || (store.state.language && store.state.language.language);

    if (!savedLang) {
      await store.dispatch("setLanguage", navigator.languages);
      savedLang = store.getters.getLanguage || (store.state.language && store.state.language.language);
    }

    // If locale differs, switch i18n locale and lazily load messages when needed
    if (savedLang && savedLang !== i18n.locale) {
      try {
        const existingMessages = i18n.getLocaleMessage ? i18n.getLocaleMessage(savedLang) : null;
        if (!existingMessages || Object.keys(existingMessages || {}).length === 0) {
          const response = await axios.get(`translations/${savedLang}`);
          if (i18n.setLocaleMessage) {
            i18n.setLocaleMessage(savedLang, response.data || {});
          }
        }
      } catch (e) {
        // Fallback silently if loading messages fails; keep navigation flowing
      }

      i18n.locale = savedLang;
    }

    // Sync Vue locale to Laravel session so Blade PDFs use same language (fire-and-forget)
    if (savedLang) {
      axios.post('sync-locale', { locale: savedLang }).catch(() => {});
    }

    next();
  });

  router.afterEach(() => {
    const gullPreLoading = document.getElementById("loading_wrap");
    // Defer hiding to global logic during initial boot; otherwise hide immediately
    if (gullPreLoading) {
      if (window.__initialLoaderActive) {
        // App.vue will set __appReadyToHideLoader; axios interceptors will call __hideInitialLoaderIfDone
        window.__hideInitialLoaderIfDone && window.__hideInitialLoaderIfDone();
      } else {
        gullPreLoading.style.display = "none";
      }
    }

    setTimeout(() => NProgress.done(), 500);

    if (window.innerWidth <= 1200) {
      store.dispatch("changeSidebarProperties");

      if (store.getters.getSideBarToggleProperties.isSecondarySideNavOpen) {
        store.dispatch("changeSecondarySidebarProperties");
      }

      if (store.getters.getCompactSideBarToggleProperties.isSideNavOpen) {
        store.dispatch("changeCompactSidebarProperties");
      }
    } else {
      if (store.getters.getSideBarToggleProperties.isSecondarySideNavOpen) {
        store.dispatch("changeSecondarySidebarProperties");
      }
    }
  });
}


async function Check_Token(to, from, next) {
    let token = to.params.token;
    const res = await axios
        .get("password/find/" + token)
        .then(response => response.data);

    if (!res.success) {
        next("/app/sessions/signIn");
    } else {
        return next();
    }
}

export default router;
