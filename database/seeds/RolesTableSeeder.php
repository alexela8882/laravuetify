<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'Super Admin',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Service Call Admin',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Service Call Computerware Admin',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Service Call Connectivity Admin',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Inventory Admin',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Inventory User',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Message Cast Admin',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Message Cast User',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Customer Photo Admin',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Customer Photo Moderator',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Customer Photo User',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Pending Admin',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Pending User',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Interview Schedule Admin',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Interview Schedule User',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Report Moderator',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Report Admin',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Employee User',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Employee Admin',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Overtime User',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Overtime Approver',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Overtime Admin',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Leave of Absences User',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Leave of Absences Approver',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Leave of Absences Admin',
            'guard_name' => 'api',
        ]);
    }
}
