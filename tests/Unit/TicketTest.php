<?php

namespace Tests\Unit;

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
        $this->get('api/ticket')->dd();
        $this->assertTrue(true);
    }
}
