<?php

namespace Tests\Feature;

use App\Models\Exchange;
use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontOfficeExchangeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_reserve_a_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $response = $this->actingAs($user)->postJson(route('user.reserveBook'), [
            'bookDemandeId' => $book->id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('exchanges', [
            'type' => 'RESERVATION',
            'status' => 'EN_ATTENTE',
            'userInitiateurId' => $user->id,
            'bookDemandeId' => $book->id,
        ]);
    }

    /** @test */
    public function user_can_confirm_an_exchange()
    {
        $user = User::factory()->create();
        $exchange = Exchange::factory()->create([
            'status' => 'EN_ATTENTE',
            'userInitiateurId' => $user->id,
        ]);

        $response = $this->actingAs($user)->patchJson(route('user.confirmExchange', $exchange->id));

        $response->assertStatus(200);
        $this->assertEquals('EN_COURS', $exchange->fresh()->status);
    }

    /** @test */
    public function user_can_view_exchange_history()
    {
        $user = User::factory()->create();
        Exchange::factory()->count(3)->create([
            'userInitiateurId' => $user->id,
        ]);

        $response = $this->actingAs($user)->getJson(route('user.exchangeHistory'));

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'exchanges');
    }
}