<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knowledge_base_article_groups', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } catch (\Throwable $e) {
            // Ignore on SQLite.
        }
        Schema::dropIfExists('knowledge_base_article_groups');
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } catch (\Throwable $e) {
            // Ignore on SQLite.
        }
    }
};
