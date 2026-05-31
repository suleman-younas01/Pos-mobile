<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserWarehouse extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_warehouse')->updateOrInsert(
            [
                'user_id' => 1,
                'warehouse_id' => 1,
            ],
            [
                'user_id' => 1,
                'warehouse_id' => 1,
            ]
        );
    }
}
