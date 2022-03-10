<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsAuthSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {  
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions

        $password = 123456;
        $role_super_admin = Role::create(['name' => 'super-admin']); // gets all permissions via Gate::before rule; see AuthServiceProvider        
        $role_admin = Role::create(['name' => 'user-admin']);
     

        $permissions_admin = ['manager'];
        $permissions_web = []; // ['product-editor'];

        foreach ($permissions_admin as $permission) {
            Permission::create(['name' => $permission])->syncRoles([$role_admin]);
            // Permission::create(['name' => $permission])->syncRoles([$role_1, $role_2]);
        }     
        
        foreach ($permissions_web as $permission) {
           // Permission::create(['name' => $permission])->syncRoles([$role_web]);
        }          

        // create demo users
        
        $user = \App\Models\User::factory()->create([
            'lastname' => 'Admin',
        	'name' => 'Super Admin', 
            'document' => 222222,
        	'email' => 'spadmin@midominio.com',
        	'password' => bcrypt($password),
        ]);
        $user->assignRole($role_super_admin);  

        $user = \App\Models\User::factory()->create([
            'lastname' => 'shernandez',
        	'name' => 'shernandez', 
            'document' => 333333,
        	'email' => 'sadmin@midominio.com',
        	'password' => bcrypt($password),
        ]);
        $user->assignRole($role_admin);        

        $user = \App\Models\User::factory()->create([
            'lastname' => 'Juan Pablo',
            'name' => 'Cardona',
            'document' => 2745878,
            'email' => 'jp@midominio.com',
            'password' => bcrypt($password),
        ]);
        $user->assignRole($role_admin);
    }
}
