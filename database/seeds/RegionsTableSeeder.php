<?php

use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('regions')->insert([
            'name' => 'Region 1',
        ]);

        DB::table('regions')->insert([
            'name' => 'Region 2',
        ]);

        DB::table('regions')->insert([
            'name' => 'Region 3',
        ]);
    }
}
