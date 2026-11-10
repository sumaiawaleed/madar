<?php

use App\Models\Appointment;
use App\Models\User;

test('a freelancer can schedule an appointment', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('appointments.store'), [
        'title' => 'مراجعة المنيو',
        'type' => 'اجتماع مرئي',
        'scheduled_on' => today()->addDay()->toDateString(),
        'starts_at' => '11:00',
        'duration_minutes' => 45,
        'location' => 'Google Meet',
    ])->assertRedirect(route('appointments.index'));

    $this->actingAs($user)->get(route('appointments.index'))
        ->assertOk()->assertSee('غدًا')->assertSee('مراجعة المنيو')->assertSee('11:00');
});

test('an appointment type must be one of the known types', function () {
    $this->actingAs(User::factory()->create())->post(route('appointments.store'), [
        'title' => 'x', 'type' => 'other', 'scheduled_on' => '2026-12-01', 'starts_at' => '10:00',
    ])->assertSessionHasErrors('type');
});

test('past appointments are listed separately', function () {
    $user = User::factory()->create();
    Appointment::factory()->for($user)->create(['title' => 'اجتماع قديم', 'scheduled_on' => today()->subDays(3)]);

    $this->actingAs($user)->get(route('appointments.index'))
        ->assertSee('لا مواعيد قادمة')->assertSee('مواعيد سابقة')->assertSee('اجتماع قديم');
});

test('a freelancer cannot delete another freelancer appointment', function () {
    $appointment = Appointment::factory()->create();

    $this->actingAs(User::factory()->create())->delete(route('appointments.destroy', $appointment))->assertForbidden();
    $this->assertModelExists($appointment);
});
