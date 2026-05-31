<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Warehouse extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('warehouses')->updateOrInsert(
            ['id' => 1],
            [
                'name' => 'Default Warehouse',
                'city' => null,
                'mobile' => null,
                'zip' => null,
                'email' => null,
                'country' => null,
                'deleted_at' => null,
            ]
        );
    }
}
