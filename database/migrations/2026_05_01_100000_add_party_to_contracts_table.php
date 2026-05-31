<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddPartyToContractsTable extends Migration
{
    private function dropForeignIfExists(string $table, string $name): void
    {
        try {
            Schema::table($table, function (Blueprint $table) use ($name) {
                $table->dropForeign($name);
            });
        } catch (\Throwable $e) {
            // Ignore missing foreign key or SQLite schema inspection differences.
        }
    }

    public function up()
    {
        if (!Schema::hasColumn('contracts', 'party_type')) {
            Schema::table('contracts', function (Blueprint $table) {
                $table->string('party_type', 20)->default('customer')->after('contract_number');
            });
        }

        if (!Schema::hasColumn('contracts', 'employee_id')) {
            Schema::table('contracts', function (Blueprint $table) {
                $table->integer('employee_id')->nullable()->after('client_id')->index();
            });
        }

        $this->dropForeignIfExists('contracts', 'contracts_client_fk');

        try {
            DB::statement('ALTER TABLE contracts MODIFY client_id INT NULL');
        } catch (\Throwable $e) {
            // SQLite does not support ALTER TABLE MODIFY; ignore on SQLite.
        }

        Schema::table('contracts', function (Blueprint $table) {
            $table->foreign('client_id', 'contracts_client_fk')
                ->references('id')->on('clients')
                ->onUpdate('RESTRICT')->onDelete('RESTRICT');

            $table->foreign('employee_id', 'contracts_employee_fk')
                ->references('id')->on('employees')
                ->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    public function down()
    {
        $this->dropForeignIfExists('contracts', 'contracts_employee_fk');
        $this->dropForeignIfExists('contracts', 'contracts_client_fk');

        Schema::table('contracts', function (Blueprint $table) {
            if (Schema::hasColumn('contracts', 'employee_id')) {
                $table->dropColumn('employee_id');
            }
            if (Schema::hasColumn('contracts', 'party_type')) {
                $table->dropColumn('party_type');
            }
        });

        try {
            DB::statement('ALTER TABLE contracts MODIFY client_id INT NOT NULL');
        } catch (\Throwable $e) {
            // SQLite does not support ALTER TABLE MODIFY; ignore on SQLite.
        }

        Schema::table('contracts', function (Blueprint $table) {
            $table->foreign('client_id', 'contracts_client_fk')
                ->references('id')->on('clients')
                ->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }
}
