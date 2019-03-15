<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
    	$role->code = "001";
    	$role->name = "Usuario";
        $role->save();

        $role = new Role();
        $role->code = "002";
        $role->name = "Vendedor";
        $role->save();

        $role = new Role();
        $role->code = "003";
        $role->name = "Administrador";
        $role->save();

        
    }
}
