<?php

namespace Tests\Feature;

use App\Models\Exchange;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExchangeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test that an exchange correctly links initiator and receiver users.
     */
    public function test_exchange_correctly_links_users(): void
    {
        // Create users
        $initiator = User::factory()->create(['name' => 'John Doe']);
        $receiver = User::factory()->create(['name' => 'Jane Smith']);
        
        // Create a book owned by the receiver
        $book = Book::factory()->create([
            'title' => 'Test Book',
            'owner_id' => $receiver->id
        ]);
        
        // Create an exchange
        $exchange = Exchange::create([
            'type' => 'RESERVATION',
            'status' => 'EN_ATTENTE',
            'dateDebut' => now(),
            'dateFin' => now()->addDays(7),
            'userInitiateurId' => $initiator->id,
            'userRecepteurId' => $receiver->id,
            'bookDemandeId' => $book->id,
        ]);
        
        // Assert that the exchange correctly links users
        $this->assertEquals($initiator->id, $exchange->userInitiateurId);
        $this->assertEquals($receiver->id, $exchange->userRecepteurId);
        
        // Test relationships
        $this->assertEquals('John Doe', $exchange->initiateur->name);
        $this->assertEquals('Jane Smith', $exchange->recepteur->name);
        $this->assertEquals('Test Book', $exchange->bookDemande->title);
    }

    /**
     * Test exchange creation through controller sets correct user IDs.
     */
    public function test_exchange_creation_sets_correct_user_ids(): void
    {
        // Create users
        $initiator = User::factory()->create();
        $receiver = User::factory()->create();
        
        // Create a book owned by the receiver
        $book = Book::factory()->create(['owner_id' => $receiver->id]);
        
        // Act as the initiator
        $this->actingAs($initiator);
        
        // Create exchange via POST request
        $response = $this->post(route('exchanges.store'), [
            'type' => 'RESERVATION',
            'status' => 'EN_ATTENTE',
            'dateDebut' => now()->toDateString(),
            'dateFin' => now()->addDays(7)->toDateString(),
            'bookDemandeId' => $book->id,
        ]);
        
        // Assert exchange was created successfully
        $response->assertRedirect(route('exchanges.index'));
        
        // Get the created exchange
        $exchange = Exchange::first();
        
        // Assert correct user IDs are set
        $this->assertEquals($initiator->id, $exchange->userInitiateurId);
        $this->assertEquals($receiver->id, $exchange->userRecepteurId);
        $this->assertEquals($book->id, $exchange->bookDemandeId);
    }

    /**
     * Test that exchange index displays both initiator and receiver information.
     */
    public function test_exchange_index_displays_user_information(): void
    {
        // Create users
        $initiator = User::factory()->create(['name' => 'John Initiator']);
        $receiver = User::factory()->create(['name' => 'Jane Receiver']);
        
        // Create a book
        $book = Book::factory()->create([
            'title' => 'Test Book Title',
            'owner_id' => $receiver->id
        ]);
        
        // Create an exchange
        Exchange::create([
            'type' => 'RESERVATION',
            'status' => 'EN_ATTENTE',
            'dateDebut' => now(),
            'dateFin' => now()->addDays(7),
            'userInitiateurId' => $initiator->id,
            'userRecepteurId' => $receiver->id,
            'bookDemandeId' => $book->id,
        ]);
        
        // Act as the initiator to view their exchanges
        $this->actingAs($initiator);
        
        // Visit the exchange index page
        $response = $this->get(route('exchanges.index'));
        
        // Assert the page loads successfully
        $response->assertStatus(200);
        
        // Assert that both user names and book title are displayed
        $response->assertSee('John Initiator');
        $response->assertSee('Jane Receiver');
        $response->assertSee('Test Book Title');
    }

    /**
     * Test that exchange show page displays detailed user information.
     */
    public function test_exchange_show_displays_detailed_user_information(): void
    {
        // Create users
        $initiator = User::factory()->create([
            'name' => 'John Initiator',
            'email' => 'john@example.com'
        ]);
        $receiver = User::factory()->create([
            'name' => 'Jane Receiver',
            'email' => 'jane@example.com'
        ]);
        
        // Create a book
        $book = Book::factory()->create([
            'title' => 'Detailed Test Book',
            'owner_id' => $receiver->id
        ]);
        
        // Create an exchange
        $exchange = Exchange::create([
            'type' => 'ECHANGE',
            'status' => 'EN_COURS',
            'dateDebut' => now(),
            'dateFin' => now()->addDays(14),
            'userInitiateurId' => $initiator->id,
            'userRecepteurId' => $receiver->id,
            'bookDemandeId' => $book->id,
        ]);
        
        // Act as the initiator
        $this->actingAs($initiator);
        
        // Visit the exchange show page
        $response = $this->get(route('exchanges.show', $exchange));
        
        // Assert the page loads successfully
        $response->assertStatus(200);
        
        // Assert that detailed user information is displayed
        $response->assertSee('John Initiator');
        $response->assertSee('john@example.com');
        $response->assertSee('Jane Receiver');
        $response->assertSee('jane@example.com');
        $response->assertSee('Detailed Test Book');
        $response->assertSee('ECHANGE');
        $response->assertSee('EN_COURS');
    }

    /**
     * Test that unauthorized users cannot view other users' exchanges.
     */
    public function test_unauthorized_user_cannot_view_exchange(): void
    {
        // Create users
        $initiator = User::factory()->create();
        $receiver = User::factory()->create();
        $otherUser = User::factory()->create();
        
        // Create a book
        $book = Book::factory()->create(['owner_id' => $receiver->id]);
        
        // Create an exchange
        $exchange = Exchange::create([
            'type' => 'RESERVATION',
            'status' => 'EN_ATTENTE',
            'dateDebut' => now(),
            'dateFin' => now()->addDays(7),
            'userInitiateurId' => $initiator->id,
            'userRecepteurId' => $receiver->id,
            'bookDemandeId' => $book->id,
        ]);
        
        // Act as a different user
        $this->actingAs($otherUser);
        
        // Try to view the exchange
        $response = $this->get(route('exchanges.show', $exchange));
        
        // Assert access is forbidden
        $response->assertStatus(403);
    }
}
