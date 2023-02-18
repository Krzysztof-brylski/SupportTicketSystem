<?php

namespace Tests\Unit;



use App\Enums\PriorityEnum;
use App\Enums\UserRolesEnum;
use App\Models\Categories;
use App\Models\Labels;
use App\Models\Ticket;
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

    public function test_standard_user_token_permission(){
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['role-user']
        );

        $category=Categories::create([
            'name'=>'test'
        ]);

        $labels=Labels::create([
            'name'=>'test'
        ]);

        $ticket= new Ticket();
        $ticket->title="test";
        $ticket->description="test";
        $ticket->priority=PriorityEnum::HIGH;
        $ticket->Categories()->associate($category);
        $ticket->Labels()->associate($labels);
        $ticket->Author()->associate($user);
        $ticket->save();

        $response=$this->get("api/logs/");
        $response->assertStatus(403);

        $response=$this->post("api/ticket/comment/{$ticket->id}");
        $response->assertStatus(403);

    }

    public function test_agent_user_token_permission(){
        Sanctum::actingAs(
            $user = User::factory(['role'=>UserRolesEnum::AGENT])->create(),
            ['role-agent']
        );

        $category=Categories::create([
            'name'=>'test'
        ]);

        $labels=Labels::create([
            'name'=>'test'
        ]);

        $ticket= new Ticket();
        $ticket->title="test";
        $ticket->description="test";
        $ticket->priority=PriorityEnum::HIGH;
        $ticket->Categories()->associate($category);
        $ticket->Labels()->associate($labels);
        $ticket->Author()->associate($user);
        $ticket->save();

        $response=$this->get("api/logs/");
        $response->assertStatus(403);

        $response=$this->post("api/ticket/comment/{$ticket->id}",array(
            'content'=>'test'
        ));
        $response->assertStatus(201);

    }
    public function test_admin_user_token_permission()
    {
        Sanctum::actingAs(
            $user = User::factory(['role' => UserRolesEnum::ADMIN])->create(),
            ['role-admin']
        );
        $response=$this->get("api/logs/");
        $response->assertStatus(200);
    }


}
