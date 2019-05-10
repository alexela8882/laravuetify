<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            'name' => 'Administer roles & permissions',
            'guard_name' => 'api',
        ]);

        // Users - Admin
        DB::table('permissions')->insert([
            'name' => 'Show Users',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Users',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Users',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Users',
            'guard_name' => 'api',
        ]);

        // User Employments - Admin
        DB::table('permissions')->insert([
            'name' => 'Show User Employments',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit User Employments',
            'guard_name' => 'api',
        ]);

        // Branches - Admin
        DB::table('permissions')->insert([
            'name' => 'Show Branches',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Branches',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Branches',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Branches',
            'guard_name' => 'api',
        ]);

        // Branch Schedules - Admin
        DB::table('permissions')->insert([
            'name' => 'Show Branch Schedules',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Branch Schedules',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Branch Schedules',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Branch Schedules',
            'guard_name' => 'api',
        ]);

        // Regions - Admin
        DB::table('permissions')->insert([
            'name' => 'Show Regions',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Regions',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Regions',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Regions',
            'guard_name' => 'api',
        ]);

        // Departments - Admin
        DB::table('permissions')->insert([
            'name' => 'Show Departments',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Departments',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Departments',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Departments',
            'guard_name' => 'api',
        ]);

        // Positions - Admin
        DB::table('permissions')->insert([
            'name' => 'Show Positions',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Positions',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Positions',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Positions',
            'guard_name' => 'api',
        ]);
    }
}
