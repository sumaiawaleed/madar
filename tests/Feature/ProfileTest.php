<?php

use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('a freelancer can update their profile and logo', function () {
    Storage::fake('public');
    $user = User::factory()->create();

    $this->actingAs($user)->put(route('profile.update'), [
        'name' => 'أحمد غالب',
        'email' => $user->email,
        'business_name' => 'استوديو أحمد',
        'handle' => '@AhmadGhaleb',
        'vat_number' => '300000000000003',
        'logo' => UploadedFile::fake()->image('logo.png'),
    ])->assertRedirect(route('profile.edit'));

    $user->refresh();
    expect($user->handle)->toBe('ahmadghaleb')->and($user->business_name)->toBe('استوديو أحمد');
    Storage::disk('public')->assertExists($user->logo_path);
});

test('a handle must be unique', function () {
    User::factory()->create(['handle' => 'taken']);
    $user = User::factory()->create();

    $this->actingAs($user)->put(route('profile.update'), ['name' => 'x', 'email' => $user->email, 'handle' => 'taken'])
        ->assertSessionHasErrors('handle');
});

test('wiping the workspace removes records but keeps the account', function () {
    $user = User::factory()->create();
    Invoice::factory()->for(Client::factory()->for($user))->create();
    $otherInvoice = Invoice::factory()->create();

    $this->actingAs($user)->delete(route('workspace.destroy'), ['confirm' => '1'])
        ->assertRedirect(route('dashboard'));

    expect($user->clients()->count())->toBe(0)
        ->and($user->invoices()->count())->toBe(0);
    $this->assertModelExists($user);
    $this->assertModelExists($otherInvoice);
});
