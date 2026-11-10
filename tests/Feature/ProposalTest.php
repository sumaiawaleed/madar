<?php

use App\Enums\ProposalStatus;
use App\Models\Client;
use App\Models\Proposal;
use App\Models\User;

test('creating a proposal without clients sends the freelancer to add one first', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('proposals.create'))
        ->assertRedirect(route('clients.create'));
});

test('a freelancer can create a numbered proposal with line items', function () {
    $user = User::factory()->create();
    $client = Client::factory()->for($user)->create();

    $this->actingAs($user)->post(route('proposals.store'), [
        'title' => 'موقع تعريفي',
        'client_id' => $client->id,
        'status' => 'sent',
        'issued_on' => '2026-11-01',
        'valid_until' => '2026-11-15',
        'items' => [
            ['description' => 'تصميم الواجهات', 'quantity' => 8, 'price' => 900],
            ['description' => '', 'quantity' => 1, 'price' => 0],
        ],
    ])->assertRedirect(route('proposals.index'));

    $proposal = $user->proposals()->sole();
    expect($proposal->number)->toBe(1)
        ->and($proposal->reference())->toBe('Q-001')
        ->and($proposal->items)->toHaveCount(1)
        ->and($proposal->subtotal())->toBe(7200.0)
        ->and($proposal->status)->toBe(ProposalStatus::Sent);
});

test('proposal numbers are sequential per freelancer', function () {
    $user = User::factory()->create();
    $client = Client::factory()->for($user)->create();
    Proposal::factory()->for($client)->count(2)->create();
    $otherProposal = Proposal::factory()->create();

    expect($user->proposals()->pluck('number')->all())->toBe([1, 2])
        ->and($otherProposal->number)->toBe(1);
});

test('a proposal cannot reference another freelancer client', function () {
    $user = User::factory()->create();
    $foreignClient = Client::factory()->create();

    $this->actingAs($user)->post(route('proposals.store'), [
        'title' => 'x',
        'client_id' => $foreignClient->id,
        'status' => 'draft',
        'issued_on' => '2026-11-01',
        'items' => [['description' => 'x', 'quantity' => 1, 'price' => 10]],
    ])->assertSessionHasErrors('client_id');
});

test('a proposal needs at least one line item', function () {
    $user = User::factory()->create();
    $client = Client::factory()->for($user)->create();

    $this->actingAs($user)->post(route('proposals.store'), [
        'title' => 'x',
        'client_id' => $client->id,
        'status' => 'draft',
        'issued_on' => '2026-11-01',
        'items' => [['description' => '', 'quantity' => 1, 'price' => 0]],
    ])->assertSessionHasErrors('items');
});

test('the proposal list can be filtered by status and offers project conversion when accepted', function () {
    $user = User::factory()->create();
    $client = Client::factory()->for($user)->create();
    $accepted = Proposal::factory()->for($client)->accepted()->create(['title' => 'عرض مقبول']);
    Proposal::factory()->for($client)->create(['title' => 'عرض مسودة']);

    $this->actingAs($user)->get(route('proposals.index', ['status' => 'accepted']))
        ->assertOk()
        ->assertSee('عرض مقبول')
        ->assertDontSee('عرض مسودة')
        ->assertSee(route('projects.create', ['proposal' => $accepted->id]), false);
});

test('a freelancer can view their proposal document but not another freelancer one', function () {
    $user = User::factory()->create(['business_name' => 'استوديو أحمد']);
    $client = Client::factory()->for($user)->create();
    $proposal = Proposal::factory()->for($client)->create(['title' => 'هوية بصرية']);

    $this->actingAs($user)->get(route('proposals.show', $proposal))
        ->assertOk()->assertSee('عرض سعر')->assertSee('استوديو أحمد')->assertSee('Q-001');

    $this->actingAs(User::factory()->create())->get(route('proposals.show', $proposal))->assertForbidden();
});
