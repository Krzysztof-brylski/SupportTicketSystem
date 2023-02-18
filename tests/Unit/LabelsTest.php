<?php

namespace Tests\Unit;

use App\Enums\PriorityEnum;
use App\Models\Categories;
use App\Models\Labels;
use App\Models\Ticket;
use App\Models\User;
use Egulias\EmailValidator\Result\Reason\LabelTooLong;
use Laravel\Sanctum\Sanctum;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;
use Tests\TestCase;

class LabelsTest extends TestCase
{
    use FastRefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create_label()
    {

        $user=User::factory()->create();
        Sanctum::actingAs($user,['role-admin']);
        $response=$this->post("api/labels/",array(
            "name"=>"test1"
        ));
        $response->assertStatus(201);
        $this->assertDatabaseHas(labels::class,[
            "name"=>"test1"
        ]);
    }

    public function test_update_label()
    {

        $user=User::factory()->create();
        Sanctum::actingAs($user,['role-admin']);
        $label = Labels::create([
            "name"=>'test1'
        ]);
        $response=$this->put("api/labels/{$label->id}",array(
            "name"=>"update"
        ));
        $response->assertStatus(200);

        $this->assertDatabaseHas(Labels::class,[
            "name"=>"update"
        ]);
    }

    public function test_update_label_validation_error()
    {

        $user=User::factory()->create();
        Sanctum::actingAs($user,['role-admin']);
        $label = Labels::create([
            "name"=>'test1'
        ]);
        $response=$this->put("api/labels/{$label->id}",array(
            "name"=>"test1"
        ));
        $response->assertStatus(422);
    }

    public function test_label_delete()
    {

        $user=User::factory()->create();
        Sanctum::actingAs($user,['role-admin']);
        $label = Labels::create([
            "name"=>'delete'
        ]);

        $response=$this->delete("api/labels/{$label->id}");
        $response->assertStatus(200);

        $this->assertDatabaseMissing(Labels::class,[
            "name"=>"delete"
        ]);
    }


    public function test_category_delete_with_ticket()
    {

        $user=User::factory()->create();
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

        $response=$this->delete("api/labels/{$labels->id}");
        $response->assertStatus(403);

    }
}
