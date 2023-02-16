<?php

namespace Tests\Unit;



use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use FastRefreshDatabase;

    public function test_user_register(){
        $response=$this->post(route("user.register"),array(
           'name'=>'mike',
           'email'=>'mike@test.pl',
           'password'=>"password",
            'password_confirmation'=>"password",
        ));
        $response->assertStatus(201);
        $this->assertDatabaseHas(User::class,array(
            'name'=>'mike',
            'email'=>'mike@test.pl',
        ));
    }

    public function test_user_login(){
        $user = User::factory()->create();
        $response=$this->post(route("user.login"),array(
            'email'=>$user->email,
            'password'=>"password",
        ));
        $response->assertStatus(200);
    }

    public function test_user_login_fail(){
        $user = User::factory()->create();
        $response=$this->post(route("user.login"),array(
            'email'=>$user->email,
            'password'=>"password123",
        ));
        $response->assertStatus(403);
    }


    public function test_auth_middleware_success(){
        Sanctum::actingAs(
            $user = User::factory()->create()
        );
        $response=$this->post(route("user.logout"));

        $response->assertStatus(200);
    }

    public function test_auth_middleware_fail(){
        $response=$this->post(route("user.logout"));
        $response->assertStatus(403);
    }
}
