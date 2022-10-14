<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
  
class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::create([
            'name' => 'Admin', 
            'email' => 'admin@1.com',
            'password' => bcrypt('12345678')
        ]);

        $user2 = User::create([
            'name' => 'Maria Eugenia Lemus', 
            'email' => 'm.lemus@donhielo.com',
            'password' => bcrypt('12345678')
        ]);

        $user3 = User::create([
            'name' => 'Milton Torres', 
            'email' => 'm.torres@donhielo.com',
            'password' => bcrypt('12345678')
        ]);
    
        $role = Role::create(['name' => 'Super-Admin']);
        $role2 = Role::create(['name' => 'Admin']);
        $role3 = Role::create(['name' => 'Base']);
     
        $permissionsSuperAdmin = Permission::pluck('id','id')->all();
        $permissionsAdmin = Permission::where('name', 'not like', '%adm%')->pluck('id','id');
        
        $permissionsBase = Permission::whereIn('name', ['sales-list', 'sales-clients-list', 'warehouse-list', 'warehouse-warehouse-list'])
                                        ->pluck('id','id');
   
        $role->syncPermissions($permissionsSuperAdmin);
        $role2->syncPermissions($permissionsAdmin);
        $role3->syncPermissions($permissionsBase);
     
        $user1->assignRole([$role->id]);
        $user2->assignRole([$role2->id]);
        $user3->assignRole([$role3->id]);
    }
}
