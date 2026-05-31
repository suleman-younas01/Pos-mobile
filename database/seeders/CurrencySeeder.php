<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->updateOrInsert(
            ['id' => 1],
            [
                'code' => 'USD',
                'name' => 'US Dollar',
                'symbol' => '$',
                'deleted_at' => null,
            ]
        );
    }
}
