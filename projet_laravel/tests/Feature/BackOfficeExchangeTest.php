<?php

namespace Tests\Feature;

use App\Models\Exchange;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BackOfficeExchangeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_supervise_exchanges()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Exchange::factory()->count(5)->create();

        $response = $this->actingAs($admin)->getJson(route('admin.superviseExchanges'));

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'exchanges');
    }

    /** @test */
    public function admin_can_arbitrate_an_exchange()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $exchange = Exchange::factory()->create(['status' => 'EN_ATTENTE']);

        $response = $this->actingAs($admin)->patchJson(route('admin.arbitrateExchange', $exchange->id), [
            'status' => 'ANNULE',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('ANNULE', $exchange->fresh()->status);
    }

    /** @test */
    public function admin_can_cancel_an_exchange()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $exchange = Exchange::factory()->create();

        $response = $this->actingAs($admin)->deleteJson(route('admin.cancelExchange', $exchange->id));

        $response->assertStatus(200);
        $this->assertEquals('ANNULE', $exchange->fresh()->status);
    }
}