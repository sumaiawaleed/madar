<?php

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('a freelancer can add a client with a logo', function () {
    Storage::fake('public');
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('clients.store'), [
        'name' => 'نورة السبيعي',
        'company' => 'مقهى دارة',
        'email' => 'noura@darah.sa',
        'logo' => UploadedFile::fake()->image('logo.png'),
    ])->assertRedirect(route('clients.index'));

    $client = $user->clients()->sole();
    expect($client->company)->toBe('مقهى دارة');
    Storage::disk('public')->assertExists($client->logo_path);
});

test('a client requires a name', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('clients.store'), ['company' => 'x'])
        ->assertSessionHasErrors('name');
});

test('the client list only shows the signed-in freelancer clients and supports search', function () {
    $user = User::factory()->create();
    Client::factory()->for($user)->create(['name' => 'ريم القحطاني']);
    Client::factory()->for($user)->create(['name' => 'فهد العتيبي']);
    Client::factory()->create(['name' => 'عميل شخص آخر']);

    $this->actingAs($user)->get(route('clients.index'))
        ->assertOk()->assertSee('ريم القحطاني')->assertDontSee('عميل شخص آخر');

    $this->actingAs($user)->get(route('clients.index', ['q' => 'ريم']))
        ->assertSee('ريم القحطاني')->assertDontSee('فهد العتيبي');
});

test('a freelancer can update and delete their client', function () {
    $user = User::factory()->create();
    $client = Client::factory()->for($user)->create();

    $this->actingAs($user)->put(route('clients.update', $client), ['name' => 'اسم جديد'])
        ->assertRedirect(route('clients.index'));
    expect($client->fresh()->name)->toBe('اسم جديد');

    $this->actingAs($user)->delete(route('clients.destroy', $client))->assertRedirect(route('clients.index'));
    $this->assertModelMissing($client);
});

test('a freelancer cannot edit, update or delete another freelancer client', function () {
    $client = Client::factory()->create();
    $intruder = User::factory()->create();

    $this->actingAs($intruder)->get(route('clients.edit', $client))->assertForbidden();
    $this->actingAs($intruder)->put(route('clients.update', $client), ['name' => 'hacked'])->assertForbidden();
    $this->actingAs($intruder)->delete(route('clients.destroy', $client))->assertForbidden();
    $this->assertModelExists($client);
});
