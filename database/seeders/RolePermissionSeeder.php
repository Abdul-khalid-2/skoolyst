<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'schools.view',
            'schools.create',
            'schools.edit',
            'schools.delete',
            'schools.manage', // For managing their own school only
            'branches.view',
            'branches.create',
            'branches.edit',
            'branches.delete',
            'events.view',
            'events.create',
            'events.edit',
            'events.delete',
            'reviews.view',
            'reviews.create',
            'reviews.edit',
            'reviews.delete',
            'users.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $schoolAdmin = Role::create(['name' => 'school-admin']);
        $schoolAdmin->givePermissionTo([
            'schools.manage',
            'branches.view',
            'branches.create',
            'branches.edit',
            'branches.delete',
            'events.view',
            'events.create',
            'events.edit',
            'events.delete',
            'reviews.view',
            'reviews.create',
            'reviews.edit',
            'reviews.delete',
        ]);

        $schoolUser = Role::create(['name' => 'school-user']);
        $schoolUser->givePermissionTo([
            'branches.view',
            'events.view',
            'reviews.view',
            'reviews.create',
        ]);
        $shopOwner = Role::create(['name' => 'shop-owner']);
        $shopOwner->givePermissionTo([
            'branches.view',
            'events.view',
            'reviews.view',
            'reviews.create',
        ]);

        // Create a super admin user
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@skoolyst.com',
            'password' => bcrypt('12345678'),
        ]);
        $user->assignRole('super-admin');
    }
}
