<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Administrador';
        $user->email = 'admin@admin.com';
        $user->password = "123456";
        $user->save();

        //Asignar rol de administrador
        $user->roles()->sync(3);
    }
}
