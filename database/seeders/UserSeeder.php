<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'super admin',
            'guard_name' => 'web'
        ]);

        Role::create([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);

        Role::create([
            'name' => 'user',
            'guard_name' => 'web'
        ]);

        $permissions = [
            'user create', 'user read', 'user update', 'user delete', 'user destroy',
            'activity create', 'activity read', 'activity update', 'activity delete',
            'role create', 'role read', 'role update', 'role delete',
            'permission create', 'permission read', 'permission update', 'permission delete',
            'assign sync', 'profile read', 'profile update',

            //posts
            'post create', 'post read', 'post update', 'post delete',
            'category create', 'category read', 'category update', 'category delete',
            'tag create', 'tag read', 'tag update', 'tag delete',

            //pages
            'page create', 'page read', 'page update', 'page delete'

        ];

        foreach ($permissions as $permit) {
            Permission::create([
                'name' => $permit,
                'guard_name' => "web"
            ]);
        }

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'username' => 'super',
            'email' => "super@email.com",
            'password' => bcrypt('password')
        ]);

        $superAdmin->assignRole('super admin');

        $admin = User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => "admin@email.com",
            'password' => bcrypt('password')
        ]);

        $admin->assignRole('admin');

        $user = User::create([
            'name' => 'User',
            'username' => 'user',
            'email' => "user@user.com",
            'password' => bcrypt('password')
        ]);

        $user->assignRole('user');




        //** give role permissions */
        $roleSuper = Role::find(1);

        $roleSuper->syncPermissions($permissions);

        $roleAdmin = Role::find(2);

        $roleAdmin->syncPermissions([
            'user create', 'user read', 'user update',

            'post create', 'post read', 'post update', 'post delete',

            'category create', 'category read', 'category update', 'category delete',
            'tag create', 'tag read', 'tag update', 'tag delete',
            //pages
            'page create', 'page read', 'page update', 'page delete'

        ]);

        $roleUser = Role::find(3);

        $roleUser->syncPermissions([
            'profile update', 'profile read',
            //post
            'post create', 'post read', 'post update', 'post delete',
        ]);



        //test bulk data username
        // for ($i = 1; $i <= 10000; $i++) {
        //     $user = User::create([
        //         'name' => 'User testing ke: ' . $i,
        //         'username' => 'usernametestingke' . $i,
        //         'email' => "email_ke_" . $i . "@email.com",
        //         'password' => bcrypt('password')
        //     ]);

        //     //test bulk data activity
        //     activity('testing_management')->causedBy($user)->withProperties($user)->performedOn($user)->log('Create User');
        // }
    }
}
