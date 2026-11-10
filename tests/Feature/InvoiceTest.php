<?php

use App\Enums\InvoiceStatus;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\User;

test('invoice totals include 15 percent VAT only when enabled', function () {
    $withVat = Invoice::factory()->make(['has_vat' => true, 'items' => [['description' => 'x', 'quantity' => 2, 'price' => 1000]]]);
    $withoutVat = Invoice::factory()->make(['has_vat' => false, 'items' => [['description' => 'x', 'quantity' => 2, 'price' => 1000]]]);

    expect($withVat->vatAmount())->toBe(300.0)
        ->and($withVat->total())->toBe(2300.0)
        ->and($withoutVat->total())->toBe(2000.0);
});

test('a freelancer can create an invoice', function () {
    $user = User::factory()->create();
    $client = Client::factory()->for($user)->create();

    $this->actingAs($user)->post(route('invoices.store'), [
        'client_id' => $client->id,
        'status' => 'sent',
        'issued_on' => '2026-11-01',
        'due_on' => '2026-11-15',
        'has_vat' => '1',
        'items' => [['description' => 'دفعة أولى', 'quantity' => 1, 'price' => 4500]],
    ])->assertRedirect(route('invoices.index'));

    $invoice = $user->invoices()->sole();
    expect($invoice->reference())->toBe('INV-0001')
        ->and($invoice->has_vat)->toBeTrue()
        ->and($invoice->total())->toBe(5175.0)
        ->and($invoice->paid_on)->toBeNull();
});

test('an unchecked VAT box stores the invoice without VAT', function () {
    $user = User::factory()->create();
    $client = Client::factory()->for($user)->create();

    $this->actingAs($user)->post(route('invoices.store'), [
        'client_id' => $client->id,
        'status' => 'draft',
        'issued_on' => '2026-11-01',
        'items' => [['description' => 'x', 'quantity' => 1, 'price' => 100]],
    ]);

    expect($user->invoices()->sole()->has_vat)->toBeFalse();
});

test('marking an invoice paid records today as the payment date', function () {
    $user = User::factory()->create();
    $invoice = Invoice::factory()->for(Client::factory()->for($user))->create();

    $this->actingAs($user)->from(route('invoices.index'))
        ->post(route('invoices.payment.store', $invoice))
        ->assertRedirect(route('invoices.index'));

    expect($invoice->fresh())
        ->status->toBe(InvoiceStatus::Paid)
        ->paid_on->toDateString()->toBe(today()->toDateString());
});

test('an overdue unpaid invoice is shown as late and can be filtered', function () {
    $user = User::factory()->create();
    $client = Client::factory()->for($user)->create();
    $late = Invoice::factory()->for($client)->overdue()->create();
    Invoice::factory()->for($client)->paid()->create();

    expect($late->displayState()['key'])->toBe('late');

    $this->actingAs($user)->get(route('invoices.index', ['state' => 'late']))
        ->assertOk()->assertSee('INV-0001')->assertDontSee('INV-0002');
});

test('a project pre-fills a new invoice', function () {
    $user = User::factory()->create();
    $project = Project::factory()->for(Client::factory()->for($user))->create(['title' => 'متجر نقش', 'budget' => 5500]);

    $this->actingAs($user)->get(route('invoices.create', ['project' => $project->id]))
        ->assertOk()->assertSee('متجر نقش')->assertSee('value="5500"', false);
});

test('the invoice document shows VAT details and IBAN', function () {
    $user = User::factory()->create(['vat_number' => '300000000000003', 'iban' => 'SA0380000000608010167519']);
    $invoice = Invoice::factory()->for(Client::factory()->for($user))->create();

    $this->actingAs($user)->get(route('invoices.show', $invoice))
        ->assertOk()->assertSee('فاتورة ضريبية')->assertSee('300000000000003')->assertSee('SA0380000000608010167519');
});

test('a freelancer cannot see or pay another freelancer invoice', function () {
    $invoice = Invoice::factory()->create();
    $intruder = User::factory()->create();

    $this->actingAs($intruder)->get(route('invoices.show', $invoice))->assertForbidden();
    $this->actingAs($intruder)->post(route('invoices.payment.store', $invoice))->assertForbidden();
    expect($invoice->fresh()->status)->toBe(InvoiceStatus::Sent);
});
