<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EmailPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [

            [
                'group_name' => 'email',
                'permissions' => [
                    'api.error',
                    'signup.request',
                    'help.request',
                    'change.order.request',
                ]
            ],           
        ];
        // $roleSuperAdmin = Role::create(['name' => 'superadmin', 'guard_name' => 'admin']);
        $roleSuperAdmin = Role::Where('name','superadmin')->where('guard_name','admin')->first();
        if(!$roleSuperAdmin) {
            $roleSuperAdmin = Role::create(['name' => 'superadmin', 'guard_name' => 'admin']);
        }

        // Create and Assign Permissions
        for ($i = 0; $i < count($permissions); $i++) {
            $permissionGroup = $permissions[$i]['group_name'];
            for ($j = 0; $j < count($permissions[$i]['permissions']); $j++) {
                // Create Permission
                $permission = Permission::create(['name' => $permissions[$i]['permissions'][$j], 'group_name' => $permissionGroup, 'guard_name' => 'admin']);
                $roleSuperAdmin->givePermissionTo($permission);
                $permission->assignRole($roleSuperAdmin);
            }
        }
        // Assign super admin role permission to superadmin user
        $admin = Admin::where('username', 'superadmin')->first();
        if ($admin) {
            $admin->assignRole($roleSuperAdmin);
        }
    }
}
