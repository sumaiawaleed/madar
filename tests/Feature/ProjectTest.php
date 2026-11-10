<?php

use App\Enums\ProjectStatus;
use App\Models\Client;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\User;

test('the project board groups projects by stage', function () {
    $user = User::factory()->create();
    $client = Client::factory()->for($user)->create();
    Project::factory()->for($client)->create(['title' => 'مشروع جارٍ']);

    $this->actingAs($user)->get(route('projects.index'))
        ->assertOk()
        ->assertSeeInOrder(['جديد', 'قيد التنفيذ', 'مشروع جارٍ', 'مراجعة العميل', 'مكتمل']);
});

test('an accepted proposal pre-fills a new project', function () {
    $user = User::factory()->create();
    $client = Client::factory()->for($user)->create();
    $proposal = Proposal::factory()->for($client)->accepted()->create([
        'title' => 'هوية مقهى',
        'items' => [['description' => 'x', 'quantity' => 2, 'price' => 1500]],
    ]);

    $this->actingAs($user)->get(route('projects.create', ['proposal' => $proposal->id]))
        ->assertOk()->assertSee('هوية مقهى')->assertSee('value="3000"', false);

    $this->actingAs($user)->post(route('projects.store'), [
        'title' => 'هوية مقهى',
        'client_id' => $client->id,
        'proposal_id' => $proposal->id,
        'status' => 'new',
        'budget' => 3000,
    ])->assertRedirect(route('projects.index'));

    expect($proposal->fresh()->canBecomeProject())->toBeFalse();
});

test('moving a project to done sets progress to 100', function () {
    $user = User::factory()->create();
    $project = Project::factory()->for(Client::factory()->for($user))->create(['progress' => 40]);

    $this->actingAs($user)->patch(route('projects.stage.update', $project), ['status' => 'done'])
        ->assertRedirect(route('projects.index'));

    expect($project->fresh())->status->toBe(ProjectStatus::Done)->progress->toBe(100);
});

test('progress must be between 0 and 100', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('projects.store'), ['title' => 'x', 'status' => 'new', 'progress' => 150])
        ->assertSessionHasErrors('progress');
});

test('a freelancer cannot move or prefill from another freelancer records', function () {
    $project = Project::factory()->create();
    $proposal = Proposal::factory()->accepted()->create();
    $intruder = User::factory()->create();

    $this->actingAs($intruder)->patch(route('projects.stage.update', $project), ['status' => 'done'])->assertForbidden();
    $this->actingAs($intruder)->get(route('projects.create', ['proposal' => $proposal->id]))->assertNotFound();
});
