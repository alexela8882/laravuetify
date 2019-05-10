<?php

use Illuminate\Database\Seeder;

class EmploymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('user_employments')->insert([
          'id' => 1,
          'user_id' => 1,
          'branch_id' => 1,
          'department_id' => 1,
          'sss' => "123",
      ]);
    }
}
