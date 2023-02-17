<?php

namespace Tests\Unit;

use App\Models\Categories;
use App\Models\Labels;
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
        Sanctum::actingAs($user);
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
        Sanctum::actingAs($user);
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
        Sanctum::actingAs($user);
        $label = Labels::create([
            "name"=>'test1'
        ]);
        $response=$this->put("api/labels/{$label->id}",array(
            "name"=>"test1"
        ));
        $response->assertStatus(302);
    }

    public function test_label_delete()
    {

        $user=User::factory()->create();
        Sanctum::actingAs($user);
        $label = Labels::create([
            "name"=>'delete'
        ]);

        $response=$this->delete("api/labels/{$label->id}");
        $response->assertStatus(200);

        $this->assertDatabaseMissing(Labels::class,[
            "name"=>"delete"
        ]);
    }

    //todo implement test for deleting label with tickets

//    public function test_category_delete_with_ticket()
//    {
//
//        $user=User::factory()->create();
//        Sanctum::actingAs($user);
//        $category = Categories::create([
//            "name"=>'delete'
//        ]);
//
//        $response=$this->delete("api/categories/{$category->id}");
//        $response->assertStatus(500);
//
//    }
}
