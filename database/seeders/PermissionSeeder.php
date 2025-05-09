<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create permissions

        $permissions = [
            'category-list',
            'category-create',
            'category-edit',
            'category-delete',
            // 'role-list',
            // 'role-create',
            // 'role-edit',
            // 'role-delete',
            // 'product-list',
            // 'product-create',
            // 'product-edit',
            // 'product-delete',
            // 'user-list',
            // 'user-create',
            // 'user-edit',
            // 'user-delete',
        ];

        foreach($permissions as $key => $permission)
        {
            Permission::create(['name' => $permission]);
        }
        
        // create roles and assighn permisisons
        $role = Role::create(['name' => 'General Admin']);
        $role->givePermissionTo(Permission::all());

    }

}
