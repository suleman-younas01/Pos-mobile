<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToContractsTables extends Migration
{
    /**
     * Drop a foreign key if it exists (safe for re-running migration).
     */
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

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop existing FKs if present (e.g. from a previous partial run) to avoid 1022 duplicate key
        $this->dropForeignIfExists('contract_tasks', 'contract_tasks_contract_fk');
        $this->dropForeignIfExists('contract_notes', 'contract_notes_user_fk');
        $this->dropForeignIfExists('contract_notes', 'contract_notes_contract_fk');
        $this->dropForeignIfExists('contract_renewals', 'contract_renewals_from_contract_fk');
        $this->dropForeignIfExists('contract_renewals', 'contract_renewals_contract_fk');
        $this->dropForeignIfExists('contract_comments', 'contract_comments_user_fk');
        $this->dropForeignIfExists('contract_comments', 'contract_comments_contract_fk');
        $this->dropForeignIfExists('contract_attachments', 'contract_attachments_contract_fk');
        $this->dropForeignIfExists('contracts', 'contracts_project_fk');
        $this->dropForeignIfExists('contracts', 'contracts_client_fk');

        Schema::table('contracts', function (Blueprint $table) {
            $table->foreign('client_id', 'contracts_client_fk')
                ->references('id')
                ->on('clients')
                ->onUpdate('RESTRICT')
                ->onDelete('RESTRICT');

            $table->foreign('project_id', 'contracts_project_fk')
                ->references('id')
                ->on('projects')
                ->onUpdate('RESTRICT')
                ->onDelete('RESTRICT');
        });

        Schema::table('contract_attachments', function (Blueprint $table) {
            $table->foreign('contract_id', 'contract_attachments_contract_fk')
                ->references('id')
                ->on('contracts')
                ->onUpdate('RESTRICT')
                ->onDelete('CASCADE');
        });

        // user_id must match users.id type (integer); contract tables use unsignedBigInteger by default
        try {
            DB::statement('ALTER TABLE contract_comments MODIFY user_id INT NOT NULL');
        } catch (\Throwable $e) {
            // SQLite does not support ALTER TABLE MODIFY; ignore on SQLite.
        }
        Schema::table('contract_comments', function (Blueprint $table) {
            $table->foreign('contract_id', 'contract_comments_contract_fk')
                ->references('id')
                ->on('contracts')
                ->onUpdate('RESTRICT')
                ->onDelete('CASCADE');

            $table->foreign('user_id', 'contract_comments_user_fk')
                ->references('id')
                ->on('users')
                ->onUpdate('RESTRICT')
                ->onDelete('RESTRICT');
        });

        Schema::table('contract_renewals', function (Blueprint $table) {
            $table->foreign('contract_id', 'contract_renewals_contract_fk')
                ->references('id')
                ->on('contracts')
                ->onUpdate('RESTRICT')
                ->onDelete('CASCADE');

            $table->foreign('renewed_from_contract_id', 'contract_renewals_from_contract_fk')
                ->references('id')
                ->on('contracts')
                ->onUpdate('RESTRICT')
                ->onDelete('SET NULL');
        });

        // user_id must match users.id type (integer); contract tables use unsignedBigInteger by default
        try {
            DB::statement('ALTER TABLE contract_notes MODIFY user_id INT NOT NULL');
        } catch (\Throwable $e) {
            // SQLite does not support ALTER TABLE MODIFY; ignore on SQLite.
        }
        Schema::table('contract_notes', function (Blueprint $table) {
            $table->foreign('contract_id', 'contract_notes_contract_fk')
                ->references('id')
                ->on('contracts')
                ->onUpdate('RESTRICT')
                ->onDelete('CASCADE');

            $table->foreign('user_id', 'contract_notes_user_fk')
                ->references('id')
                ->on('users')
                ->onUpdate('RESTRICT')
                ->onDelete('RESTRICT');
        });

        Schema::table('contract_tasks', function (Blueprint $table) {
            $table->foreign('contract_id', 'contract_tasks_contract_fk')
                ->references('id')
                ->on('contracts')
                ->onUpdate('RESTRICT')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropForeign(['project_id']);
        });

        Schema::table('contract_attachments', function (Blueprint $table) {
            $table->dropForeign(['contract_id']);
        });

        Schema::table('contract_comments', function (Blueprint $table) {
            $table->dropForeign(['contract_id']);
            $table->dropForeign(['user_id']);
        });
        try {
            DB::statement('ALTER TABLE contract_comments MODIFY user_id BIGINT UNSIGNED NOT NULL');
        } catch (\Throwable $e) {
            // SQLite does not support ALTER TABLE MODIFY; ignore on SQLite.
        }

        Schema::table('contract_renewals', function (Blueprint $table) {
            $table->dropForeign(['contract_id']);
            $table->dropForeign(['renewed_from_contract_id']);
        });

        Schema::table('contract_notes', function (Blueprint $table) {
            $table->dropForeign(['contract_id']);
            $table->dropForeign(['user_id']);
        });
        try {
            DB::statement('ALTER TABLE contract_notes MODIFY user_id BIGINT UNSIGNED NOT NULL');
        } catch (\Throwable $e) {
            // SQLite does not support ALTER TABLE MODIFY; ignore on SQLite.
        }

        Schema::table('contract_tasks', function (Blueprint $table) {
            $table->dropForeign(['contract_id']);
        });
    }
}
