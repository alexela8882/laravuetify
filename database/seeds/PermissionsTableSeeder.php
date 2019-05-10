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

        // User Authorizations - Admin
        DB::table('permissions')->insert([
            'name' => 'Show User Authorizations',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit User Authorizations',
            'guard_name' => 'api',
        ]);

        // Overtime - User
        DB::table('permissions')->insert([
            'name' => 'Show Overtimes',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Overtimes',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Overtimes',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Overtimes',
            'guard_name' => 'api',
        ]);

        // Overtime - Approver
        DB::table('permissions')->insert([
            'name' => 'Approve Overtimes',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Return Overtimes',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Reject Overtimes',
            'guard_name' => 'api',
        ]);

        // Overtime - Admin
        DB::table('permissions')->insert([
            'name' => 'Overlook Overtimes',
            'guard_name' => 'api',
        ]);

        // Leave of Absence - User
        DB::table('permissions')->insert([
            'name' => 'Show Leave of Absences',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Leave of Absences',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Leave of Absences',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Leave of Absences',
            'guard_name' => 'api',
        ]);

        // Leave of Absence - Approver
        DB::table('permissions')->insert([
            'name' => 'Approve Leave of Absences',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Return Leave of Absences',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Reject Leave of Absences',
            'guard_name' => 'api',
        ]);

        // Leave of Absence - Admin
        DB::table('permissions')->insert([
            'name' => 'Overlook Leave of Absences',
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

        // Access Charts - Admin
        DB::table('permissions')->insert([
            'name' => 'Show Access Charts',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Access Charts',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Access Charts',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Access Charts',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Show Approving Officers',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Assign Approving Officers',
            'guard_name' => 'api',
        ]);

        // Product Items
        DB::table('permissions')->insert([
            'name' => 'Show Product Items',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Product Items',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Product Items',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Product Items',
            'guard_name' => 'api',
        ]);

        // Product Brands
        DB::table('permissions')->insert([
            'name' => 'Show Product Brands',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Product Brands',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Product Brands',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Product Brands',
            'guard_name' => 'api',
        ]);

        // Product Categories
        DB::table('permissions')->insert([
            'name' => 'Show Product Categories',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Product Categories',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Product Categories',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Product Categories',
            'guard_name' => 'api',
        ]);

        // Computerware Tickets
        DB::table('permissions')->insert([
            'name' => 'Show Computerware Tickets',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Computerware Tickets',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Computerware Tickets',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Computerware Tickets',
            'guard_name' => 'api',
        ]);

        // Power Interruptions
        DB::table('permissions')->insert([
            'name' => 'Show Power Interruptions',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Power Interruptions',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Power Interruptions',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Power Interruptions',
            'guard_name' => 'api',
        ]);

        // Service Categories
        DB::table('permissions')->insert([
            'name' => 'Show Service Categories',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Service Categories',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Service Categories',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Service Categories',
            'guard_name' => 'api',
        ]);

        // Service Providers
        DB::table('permissions')->insert([
            'name' => 'Show Service Providers',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Service Providers',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Service Providers',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Service Providers',
            'guard_name' => 'api',
        ]);

        // Service types
        DB::table('permissions')->insert([
            'name' => 'Show Service Types',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Service Types',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Service Types',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Service Types',
            'guard_name' => 'api',
        ]);

        // Connectivity Tickets
        DB::table('permissions')->insert([
            'name' => 'Show Connectivity Tickets',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Connectivity Tickets',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Connectivity Tickets',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Connectivity Tickets',
            'guard_name' => 'api',
        ]);

        // Inventories
        DB::table('permissions')->insert([
            'name' => 'Show Inventories',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Inventories',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Inventories',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Inventories',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Show Inventory Breakdown',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Show Inventory Discrepancies',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Get Inventory Raw Files',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Import Inventories',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'View Inventories',
            'guard_name' => 'api',
        ]);

        // Message
        DB::table('permissions')->insert([
            'name' => 'Compose Messages',
            'guard_name' => 'api',
        ]);

        // Message Cast Settings
        DB::table('permissions')->insert([
            'name' => 'Edit Message Cast Settings',
            'guard_name' => 'api',
        ]);

        // Contact Lists
        DB::table('permissions')->insert([
            'name' => 'Show Contact Lists',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Contact Lists',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Contact Lists',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Contact Lists',
            'guard_name' => 'api',
        ]);

        // Customers
        DB::table('permissions')->insert([
            'name' => 'Show Customers',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Customers',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Customers',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Customers',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Show Customer Files',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Customer Files',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Customer Files',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Customer Files',
            'guard_name' => 'api',
        ]);

        // Pendings
        DB::table('permissions')->insert([
            'name' => 'Show Pendings',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Pendings',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Pendings',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Readd Pendings',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Pendings',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Access Pending Charts',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Show Pending Charts',
            'guard_name' => 'api',
        ]);

        // Interview Schedules
        DB::table('permissions')->insert([
            'name' => 'Show Interview Schedules',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Interview Schedules',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Interview Schedules',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Interview Schedules',
            'guard_name' => 'api',
        ]);

        // Reports - Biometric & DTR Reports
        DB::table('permissions')->insert([
            'name' => 'Generate Biometric Reports',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Generate DTR Reports',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Import Daily Time Record Logs',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Generate Overtime Reports',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Generate Leave of Absence Reports',
            'guard_name' => 'api',
        ]);

        // Employee
        DB::table('permissions')->insert([
            'name' => 'Show Employees',
            'guard_name' => 'api',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Employees',
            'guard_name' => 'api',
        ]);
    }
}
