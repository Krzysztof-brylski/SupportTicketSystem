<?php

namespace Tests\Unit;

use App\Models\Categories;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    use FastRefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create_category()
    {

        $user=User::factory()->create();
        Sanctum::actingAs($user);
        $response=$this->post("api/categories/",array(
            "name"=>"test1"
        ));
        $response->assertStatus(201);
        $this->assertDatabaseHas(Categories::class,[
            "name"=>"test1"
        ]);
    }

    public function test_update_category()
    {

        $user=User::factory()->create();
        Sanctum::actingAs($user);
        $category = Categories::create([
            "name"=>'test1'
        ]);
        $response=$this->put("api/categories/{$category->id}",array(
            "name"=>"update"
        ));
        $response->assertStatus(200);

        $this->assertDatabaseHas(Categories::class,[
            "name"=>"update"
        ]);
    }

    public function test_update_category_validation_error()
    {

        $user=User::factory()->create();
        Sanctum::actingAs($user);
        $category = Categories::create([
            "name"=>'test1'
        ]);
        $response=$this->put("api/categories/{$category->id}",array(
            "name"=>"test1"
        ));
        $response->assertStatus(302);
    }

    public function test_category_delete()
    {

        $user=User::factory()->create();
        Sanctum::actingAs($user);
        $category = Categories::create([
            "name"=>'delete'
        ]);

        $response=$this->delete("api/categories/{$category->id}");
        $response->assertStatus(200);

        $this->assertDatabaseMissing(Categories::class,[
            "name"=>"delete"
        ]);
    }

    //todo implement test for deleting category with tickets

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
