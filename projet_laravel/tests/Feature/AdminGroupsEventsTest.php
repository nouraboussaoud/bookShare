<?php

namespace Tests\Feature;

use App\Models\ReadingGroup;
use App\Models\GroupEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminGroupsEventsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function admin_can_view_groupes_index()
    {
        ReadingGroup::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('admin.groupes.index'));

        $response->assertOk();
        $response->assertSee('Gestion Groupes');
    }

    /** @test */
    public function admin_can_view_group_show_and_edit()
    {
        $group = ReadingGroup::factory()->create();

        $show = $this->actingAs($this->admin)->get(route('admin.groupes.show', $group));
        $show->assertOk();
        $show->assertSee($group->name);

        $edit = $this->actingAs($this->admin)->get(route('admin.groupes.edit', $group));
        $edit->assertOk();
        $edit->assertSee('Modifier le Groupe');
    }

    /** @test */
    public function admin_can_view_evenements_index_and_pages()
    {
        GroupEvent::factory()->count(2)->create();

        $resp = $this->actingAs($this->admin)->get(route('admin.evenements.index'));
        $resp->assertOk();
        $resp->assertSee('Gestion Événements');

        $event = GroupEvent::factory()->create();
        $show = $this->actingAs($this->admin)->get(route('admin.evenements.show', $event));
        $show->assertOk();
        $show->assertSee($event->title);

        $edit = $this->actingAs($this->admin)->get(route('admin.evenements.edit', $event));
        $edit->assertOk();
        $edit->assertSee("Modifier l'Événement");
    }
}
