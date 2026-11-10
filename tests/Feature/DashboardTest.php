<?php

use App\Enums\ProjectStatus;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\User;

test('the dashboard summarises earnings, outstanding invoices and active projects', function () {
    $user = User::factory()->create(['name' => 'أحمد غالب']);
    $client = Client::factory()->for($user)->create(['company' => 'مقهى دارة']);
    Invoice::factory()->for($client)->paid()->create(['items' => [['description' => 'هوية', 'quantity' => 1, 'price' => 4500]]]);
    Invoice::factory()->for($client)->overdue()->create(['items' => [['description' => 'دفعة', 'quantity' => 1, 'price' => 1000]]]);
    Project::factory()->for($client)->create(['title' => 'هوية مقهى دارة', 'status' => ProjectStatus::Review]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('أحمد')
        ->assertSee('4,500')
        ->assertSee('1,150')
        ->assertSeeText('1 متأخرة')
        ->assertSee('هوية مقهى دارة');
});

test('the dashboard shows empty states for a new account', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('لا مواعيد قادمة')
        ->assertSee('لا فواتير بعد');
});

test('the earnings page totals paid invoices per client', function () {
    $user = User::factory()->create();
    $client = Client::factory()->for($user)->create(['company' => 'متجر نقش']);
    Invoice::factory()->for($client)->paid()->create(['items' => [['description' => 'x', 'quantity' => 2, 'price' => 1000]]]);

    $this->actingAs($user)
        ->get(route('earnings'))
        ->assertOk()
        ->assertSee('متجر نقش')
        ->assertSee('2,000')
        ->assertSee('300');
});
