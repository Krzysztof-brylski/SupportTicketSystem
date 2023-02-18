<?php

namespace Tests\Unit;

use App\Enums\PriorityEnum;
use App\Enums\StatusEnum;
use App\Enums\UserRolesEnum;
use App\Models\Categories;
use App\Models\Coments;
use App\Models\Labels;
use App\Models\logs;
use App\Models\Ticket;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use FastRefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_ticket_created()
    {
        $user=User::factory()->create();
        Sanctum::actingAs($user);
        $category=Categories::create([
            'name'=>'test'
        ]);

        $labels=Labels::create([
            'name'=>'test'
        ]);
        $response=$this->post('api/ticket',array(
            'title'=>"test_ticket",
            'description'=>'test_ticket',
            'priority'=>PriorityEnum::HIGH,
            'category_id'=>$category->id,
            'label_id'=>$labels->id
        ));
        $response->assertStatus(201);

        $this->assertDatabaseHas(Ticket::class,array(
            'title'=>"test_ticket",
            'description'=>'test_ticket',
            'priority'=>PriorityEnum::HIGH,
            'category_id'=>$category->id,
            'label_id'=>$labels->id
        ));
    }

    public function test_ticket_logs_created()
    {
        $user=User::factory()->create();
        Sanctum::actingAs($user);
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

        $this->assertDatabaseHas(logs::class,array(
            'logable_id'=>$ticket->id,
            'actionName'=>'ticket created',
            'logable_type'=>"App\\Models\\Ticket"
        ));

    }
    public function test_ticket_agent_assigned_and_check_for_logs(){
        $user=User::factory()->create();
        $agent=User::factory(['role'=>UserRolesEnum::AGENT])->create();
        Sanctum::actingAs($user,['role-admin']);
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


        $response=$this->put("api/ticket/assign/{$ticket->id}",array(
            'agent_id'=>$agent->id
        ));

        $response->assertStatus(200);

        $this->assertDatabaseHas(Ticket::class,array(
            'id'=>$ticket->id,
            'agent_id'=>$agent->id,
        ));

        $this->assertDatabaseHas(logs::class,array(
            'logable_id'=>$ticket->id,
            'actionName'=>'agent assigned',
            'logable_type'=>"App\\Models\\Ticket"
        ));

    }
    public function test_ticket_status_change_and_check_for_logs(){
        $user=User::factory(['role'=>UserRolesEnum::AGENT])->create();

        Sanctum::actingAs($user,['role-admin']);
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

        $response=$this->put("api/ticket/update/status/{$ticket->id}",array(
            'status'=>StatusEnum::CLOSED
        ));

        $response->assertStatus(200);

        $this->assertDatabaseHas(Ticket::class,array(
            'id'=>$ticket->id,
            'status'=>StatusEnum::CLOSED,
        ));

        $this->assertDatabaseHas(logs::class,array(
            'logable_id'=>$ticket->id,
            'actionName'=>'status updated',
            'logable_type'=>"App\\Models\\Ticket"
        ));
    }
    public function test_ticket_make_comment_and_check_for_logs(){
        $user=User::factory(['role'=>UserRolesEnum::AGENT])->create();

        Sanctum::actingAs($user,['role-admin']);
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


        $response=$this->post("api/ticket/comment/{$ticket->id}",array(
            'content'=>'test test test'
        ));

        $response->assertStatus(201);

        $this->assertDatabaseHas(Coments::class,array(
            'ticket_id'=>$ticket->id,
        ));

        $this->assertDatabaseHas(logs::class,array(
            'logable_id'=>$ticket->id,
            'actionName'=>'ticket commented',
            'logable_type'=>"App\\Models\\Ticket"
        ));
    }


}
