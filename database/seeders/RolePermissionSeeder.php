<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Class RolePermissionSeeder.
 *
 * @see https://spatie.be/docs/laravel-permission/v5/basic-usage/multiple-guards
 */
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Permission List as array.
        $permissions = [

            [
                'group_name' => 'dashboard',
                'permissions' => [
                    'dashboard.view',
                    'dashboard.edit',
                ],
            ],
            [
                'group_name' => 'blog',
                'permissions' => [
                    // Blog Permissions
                    'blog.create',
                    'blog.view',
                    'blog.edit',
                    'blog.delete',
                    'blog.approve',
                ],
            ],
            [
                'group_name' => 'user',
                'permissions' => [
                    'user.create',
                    'user.view',
                    'user.edit',
                    'user.delete',
                    'user.approve',
                    'user.login_as',
                ],
            ],
            [
                'group_name' => 'role',
                'permissions' => [
                    'role.create',
                    'role.view',
                    'role.edit',
                    'role.delete',
                    'role.approve',
                ],
            ],
            [
                'group_name' => 'module',
                'permissions' => [
                    'module.create',
                    'module.view',
                    'module.edit',
                    'module.delete',
                ],
            ],
            [
                'group_name' => 'profile',
                'permissions' => [
                    'profile.view',
                    'profile.edit',
                    'profile.delete',
                    'profile.update',
                ],
            ],
            [
                'group_name' => 'monitoring',
                'permissions' => [
                    'pulse.view',
                    'actionlog.view',
                ],
            ],
            [
                'group_name' => 'settings',
                'permissions' => [
                    'settings.edit',
                ],
            ],
            [
                'group_name' => 'pelanggan',
                'permissions' => [
                    'pelanggan.create',
                    'pelanggan.view',
                    'pelanggan.edit',
                    'pelanggan.delete',
                ],
            ],
            [
                'group_name' => 'jaringan',
                'permissions' => [
                    'jaringan.create',
                    'jaringan.view',
                    'jaringan.edit',
                    'jaringan.delete',
                ],
            ],
            [
                'group_name' => 'layanan',
                'permissions' => [
                    'layanan.create',
                    'layanan.view',
                    'layanan.edit',
                    'layanan.delete',
                ],
            ],
            [
                'group_name' => 'keluhan',
                'permissions' => [
                    'keluhan.create',
                    'keluhan.view',
                    'keluhan.edit',
                    'keluhan.delete',
                ],
            ],
            [
                'group_name' => 'spk',
                'permissions' => [
                    'spk.create',
                    'spk.view',
                    'spk.edit',
                    'spk.delete',
                ],
            ],
            [
                'group_name' => 'invoice',
                'permissions' => [
                    'invoice.create',
                    'invoice.view',
                    'invoice.edit',
                    'invoice.delete',
                ],
            ],
            [
                'group_name' => 'kategori',
                'permissions' => [
                    'kategori.create',
                    'kategori.view',
                    'kategori.edit',
                    'kategori.delete',
                ],
            ],
        ];

        // Create and Assign Permissions
        // for ($i = 0; $i < count($permissions); $i++) {
        //     $permissionGroup = $permissions[$i]['group_name'];
        //     for ($j = 0; $j < count($permissions[$i]['permissions']); $j++) {
        //         // Create Permission
        //         $permission = Permission::create(['name' => $permissions[$i]['permissions'][$j], 'group_name' => $permissionGroup]);
        //         $roleSuperAdmin->givePermissionTo($permission);
        //         $permission->assignRole($roleSuperAdmin);
        //     }
        // }

        // Do same for the admin guard for tutorial purposes.
        $user = User::where('username', 'superadmin')->first();
        $roleSuperAdmin = $this->maybeCreateSuperAdminRole();

        // Create and Assign Permissions
        for ($i = 0; $i < count($permissions); $i++) {
            $permissionGroup = $permissions[$i]['group_name'];
            for ($j = 0; $j < count($permissions[$i]['permissions']); $j++) {
                $permissionExist = Permission::where('name', $permissions[$i]['permissions'][$j])->first();
                if (is_null($permissionExist)) {
                    $permissionExist = Permission::create(
                        [
                            'name' => $permissions[$i]['permissions'][$j],
                            'group_name' => $permissionGroup,
                            'guard_name' => 'web',
                        ]
                    );
                }
                $roleSuperAdmin->givePermissionTo($permissionExist);
                $permissionExist->assignRole($roleSuperAdmin);
            }
        }

        // Assign super admin role permission to superadmin user.
        // Assign super admin role permission to superadmin user.
        if ($user) {
            $user->assignRole($roleSuperAdmin);
        }

        // Assign teknisi role permission to teknisi user.
        $teknisiPermissions = [
            'dashboard.view',
            'keluhan.view',
            'spk.view',
        ];

        // Create Teknisi Role.
        $roleTeknisi = $this->maybeCreateTeknisiRole();

        // Add the permissions to the teknisi role.
        foreach ($teknisiPermissions as $permission) {
            $roleTeknisi->givePermissionTo($permission);
        }

        $this->command->info('Teknisi role permissions added successfully!');

        // Assign Teknisi role to teknisi user
        $teknisiUser = User::where('username', 'teknisi')->first();
        if ($teknisiUser) {
            $teknisiUser->assignRole($roleTeknisi);
        }

        $this->command->info('Roles and Permissions created successfully!');
    }

    private function maybeCreateSuperAdminRole(): Role
    {
        return Role::firstOrCreate(
            ['name' => 'Superadmin', 'guard_name' => 'web'],
            ['name' => 'Superadmin', 'guard_name' => 'web']
        );
    }

    private function maybeCreateTeknisiRole(): Role
    {
        return Role::firstOrCreate(
            ['name' => 'Teknisi', 'guard_name' => 'web']
        );
    }
}
