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
            'article-list',
            'article-create',
            'article-edit',
            'article-delete',
            'article-view',
            'category-list',
            'category-create',
            'category-edit',
            'category-delete',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'adverts-list',
            'adverts-create',
            'adverts-edit',
            'adverts-delete',
            'subscribers-list',
            'subscribers-create',
            'subscribers-edit',
            'subscribers-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'resources-list',
            'resources-create',
            'resources-edit',
            'resources-delete',
            'policies-list',
            'policies-create',
            'policies-edit',
            'policies-delete',
            'partners-list',
            'partners-create',
            'partners-edit',
            'partners-delete',
            'team-list',
            'team-create',
            'team-edit',
            'team-delete',
            'join-us-edit',
            'support-edit',
            'programs-list',
            'programs-create',
            'programs-edit',
            'programs-delete',
            'comments-list',
            'comments-approve',
            'comments-reject',
            'comments-delete',
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
