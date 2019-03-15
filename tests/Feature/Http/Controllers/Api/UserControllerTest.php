<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Auth;

class UserControllerTest extends TestCase
{
    /** 
     *  @test
    */
    public function can_create_a_user()
    {
        $this->loginUser(1);

        $response = $this->json('POST',route('user.ct'),[
            'name' => 'nombre',
            'email' => 'nombre@test.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'roles' => ['1','2']
        ]);

        $this->assertDatabaseHas('users',[
            'name' => 'nombre',
            'email' => 'nombre@test.com',
        ]);

        $response->assertStatus(200);
    }

    /** 
     *  @test
    */
    public function can_edit_a_user()
    {
        $this->loginUser(1);
        // Editar el id por el id que corresponda al usuario 
        $response = $this->json('POST',route('user.ct'),[
            'id' => 10,
            'name' => 'Nuevo nombre',
            'email' => 'nombre@test.com',
            'roles' => ['1','2']
        ]);

        $this->assertDatabaseHas('users',[
            'name' => 'Nuevo nombre',
            'email' => 'nombre@test.com',
        ]);

        $response->assertStatus(200);
    }

    /** 
     *  @test
    */
    public function can_list_user()
    {
        $this->loginUser(1);
        // en name se pone lo que se quiere filtrar por nombre
        // en role_id se pone el id de rol que se quiere filtrar
        $response = $this->json('POST',route('user.list'),[
            'name' => '',
            'role_id' => 1
        ]);

        $response->assertStatus(200);
    }

    /** 
     *  @test
    */
    public function can_delete_user()
    {
        $this->loginUser(1);
        
        $response = $this->json('POST',route('user.delete'),[
            'id' => 2,
        ]);

        $response->assertStatus(200);
    }

    /** 
     *  @test
    */
    public function can_get_detail_user()
    {
        $this->loginUser(1);
        
        $response = $this->json('POST',route('user.detail'),[
            'id' => 1,
        ]);

        $response->assertStatus(200);
    }

    /** 
     *  @test
    */
    public function loginUser($id)
    {
        $user = User::where('id',$id)->first();
        Auth::login($user);
    }
}
