<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Report;
use App\Models\Exchange;
use App\Models\Book;
use App\Models\Category;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $admin;
    protected $reportedUser;
    protected $exchange;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->user = User::factory()->create(['role' => 'user']);
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->reportedUser = User::factory()->create(['role' => 'user']);

        // Create test data for exchange report
        $category = Category::factory()->create();
        $book = Book::factory()->create(['category_id' => $category->id]);
        $this->exchange = Exchange::factory()->create([
            'initiateur_id' => $this->user->id,
            'recepteur_id' => $this->reportedUser->id,
            'book_demande_id' => $book->id,
        ]);
    }

    /** @test */
    public function user_can_view_reports_index()
    {
        $report = Report::factory()->create([
            'reporter_id' => $this->user->id,
            'reported_user_id' => $this->reportedUser->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('reports.index'));

        $response->assertOk();
        $response->assertSee($report->description);
    }

    /** @test */
    public function user_can_create_behavior_report()
    {
        $response = $this->actingAs($this->user)->post(route('reports.store'), [
            'type' => Report::TYPE_COMPORTEMENT,
            'description' => 'This user has inappropriate behavior',
            'reported_user_id' => $this->reportedUser->id,
        ]);

        $response->assertRedirect(route('reports.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reports', [
            'type' => Report::TYPE_COMPORTEMENT,
            'description' => 'This user has inappropriate behavior',
            'reporter_id' => $this->user->id,
            'reported_user_id' => $this->reportedUser->id,
            'status' => Report::STATUS_EN_ATTENTE,
        ]);
    }

    /** @test */
    public function user_can_create_exchange_conflict_report()
    {
        $response = $this->actingAs($this->user)->post(route('reports.store'), [
            'type' => Report::TYPE_CONFLIT_ECHANGE,
            'description' => 'There is a conflict with this exchange',
            'exchange_id' => $this->exchange->id,
        ]);

        $response->assertRedirect(route('reports.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reports', [
            'type' => Report::TYPE_CONFLIT_ECHANGE,
            'description' => 'There is a conflict with this exchange',
            'reporter_id' => $this->user->id,
            'exchange_id' => $this->exchange->id,
            'status' => Report::STATUS_EN_ATTENTE,
        ]);
    }

    /** @test */
    public function user_cannot_report_themselves()
    {
        $response = $this->actingAs($this->user)->post(route('reports.store'), [
            'type' => Report::TYPE_COMPORTEMENT,
            'description' => 'Self-reporting test',
            'reported_user_id' => $this->user->id,
        ]);

        $response->assertSessionHasErrors('reported_user_id');
        $this->assertDatabaseMissing('reports', [
            'reporter_id' => $this->user->id,
            'reported_user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function user_can_only_report_exchanges_they_participate_in()
    {
        $otherUser = User::factory()->create();
        $category = Category::factory()->create();
        $book = Book::factory()->create(['category_id' => $category->id]);
        $otherExchange = Exchange::factory()->create([
            'initiateur_id' => $otherUser->id,
            'recepteur_id' => $this->reportedUser->id,
            'book_demande_id' => $book->id,
        ]);

        $response = $this->actingAs($this->user)->post(route('reports.store'), [
            'type' => Report::TYPE_CONFLIT_ECHANGE,
            'description' => 'Trying to report an exchange I am not part of',
            'exchange_id' => $otherExchange->id,
        ]);

        $response->assertSessionHasErrors('exchange_id');
        $this->assertDatabaseMissing('reports', [
            'reporter_id' => $this->user->id,
            'exchange_id' => $otherExchange->id,
        ]);
    }

    /** @test */
    public function user_can_view_their_own_report()
    {
        $report = Report::factory()->create([
            'reporter_id' => $this->user->id,
            'reported_user_id' => $this->reportedUser->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('reports.show', $report));

        $response->assertOk();
        $response->assertSee($report->description);
    }

    /** @test */
    public function user_cannot_view_others_reports()
    {
        $otherUser = User::factory()->create();
        $report = Report::factory()->create([
            'reporter_id' => $otherUser->id,
            'reported_user_id' => $this->reportedUser->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('reports.show', $report));

        $response->assertForbidden();
    }

    /** @test */
    public function admin_can_view_all_reports()
    {
        $report1 = Report::factory()->create(['reporter_id' => $this->user->id]);
        $report2 = Report::factory()->create(['reporter_id' => $this->reportedUser->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports.index'));

        $response->assertOk();
        $response->assertSee($report1->description);
        $response->assertSee($report2->description);
    }

    /** @test */
    public function admin_can_view_specific_report()
    {
        $report = Report::factory()->create([
            'reporter_id' => $this->user->id,
            'reported_user_id' => $this->reportedUser->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports.show', $report));

        $response->assertOk();
        $response->assertSee($report->description);
    }

    /** @test */
    public function admin_can_update_report_status_to_processed()
    {
        $report = Report::factory()->create([
            'reporter_id' => $this->user->id,
            'status' => Report::STATUS_EN_ATTENTE,
        ]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.reports.updateStatus', $report), [
                'status' => Report::STATUS_TRAITE,
            ]);

        $response->assertRedirect(route('admin.reports.show', $report));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status' => Report::STATUS_TRAITE,
        ]);

        // Check that notification was sent to reporter
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->user->id,
            'type' => 'report_status_change',
        ]);
    }

    /** @test */
    public function admin_can_update_report_status_to_rejected()
    {
        $report = Report::factory()->create([
            'reporter_id' => $this->user->id,
            'status' => Report::STATUS_EN_ATTENTE,
        ]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.reports.updateStatus', $report), [
                'status' => Report::STATUS_REJETE,
            ]);

        $response->assertRedirect(route('admin.reports.show', $report));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status' => Report::STATUS_REJETE,
        ]);

        // Check that notification was sent to reporter
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->user->id,
            'type' => 'report_status_change',
        ]);
    }

    /** @test */
    public function admin_can_delete_report()
    {
        $report = Report::factory()->create(['reporter_id' => $this->user->id]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.reports.destroy', $report));

        $response->assertRedirect(route('admin.reports.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('reports', ['id' => $report->id]);
    }

    /** @test */
    public function admin_can_bulk_update_reports_status()
    {
        $report1 = Report::factory()->create([
            'reporter_id' => $this->user->id,
            'status' => Report::STATUS_EN_ATTENTE,
        ]);
        $report2 = Report::factory()->create([
            'reporter_id' => $this->reportedUser->id,
            'status' => Report::STATUS_EN_ATTENTE,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.reports.bulkUpdateStatus'), [
                'reports' => [$report1->id, $report2->id],
                'status' => Report::STATUS_TRAITE,
            ]);

        $response->assertRedirect(route('admin.reports.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reports', [
            'id' => $report1->id,
            'status' => Report::STATUS_TRAITE,
        ]);
        $this->assertDatabaseHas('reports', [
            'id' => $report2->id,
            'status' => Report::STATUS_TRAITE,
        ]);
    }

    /** @test */
    public function regular_user_cannot_access_admin_reports()
    {
        $response = $this->actingAs($this->user)->get(route('admin.reports.index'));
        $response->assertForbidden();
    }

    /** @test */
    public function regular_user_cannot_update_report_status()
    {
        $report = Report::factory()->create(['reporter_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->patch(route('admin.reports.updateStatus', $report), [
                'status' => Report::STATUS_TRAITE,
            ]);

        $response->assertForbidden();
    }

    /** @test */
    public function creating_report_notifies_admins()
    {
        $this->actingAs($this->user)->post(route('reports.store'), [
            'type' => Report::TYPE_COMPORTEMENT,
            'description' => 'This user has inappropriate behavior',
            'reported_user_id' => $this->reportedUser->id,
        ]);

        // Check that notification was sent to admin
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->admin->id,
            'type' => 'new_report',
        ]);
    }

    /** @test */
    public function report_relationships_work_correctly()
    {
        $report = Report::factory()->create([
            'reporter_id' => $this->user->id,
            'reported_user_id' => $this->reportedUser->id,
            'exchange_id' => $this->exchange->id,
        ]);

        $this->assertEquals($this->user->id, $report->reporter->id);
        $this->assertEquals($this->reportedUser->id, $report->reportedUser->id);
        $this->assertEquals($this->exchange->id, $report->exchange->id);
    }

    /** @test */
    public function report_scopes_work_correctly()
    {
        Report::factory()->create(['status' => Report::STATUS_EN_ATTENTE]);
        Report::factory()->create(['status' => Report::STATUS_TRAITE]);
        Report::factory()->create(['status' => Report::STATUS_REJETE]);

        $this->assertEquals(1, Report::pending()->count());
        $this->assertEquals(1, Report::processed()->count());
        $this->assertEquals(1, Report::rejected()->count());
    }

    /** @test */
    public function exchange_conflict_report_requires_exchange()
    {
        $response = $this->actingAs($this->user)->post(route('reports.store'), [
            'type' => Report::TYPE_CONFLIT_ECHANGE,
            'description' => 'Exchange conflict without exchange ID',
        ]);

        $response->assertSessionHasErrors('exchange_id');
    }

    /** @test */
    public function behavior_report_requires_reported_user()
    {
        $response = $this->actingAs($this->user)->post(route('reports.store'), [
            'type' => Report::TYPE_COMPORTEMENT,
            'description' => 'Behavior report without reported user',
        ]);

        $response->assertSessionHasErrors('reported_user_id');
    }
}
