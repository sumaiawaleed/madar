<?php

use App\Models\User;

test('guests can view the login and register pages', function () {
    $this->get(route('login'))->assertOk()->assertSee('تسجيل الدخول');
    $this->get(route('register'))->assertOk()->assertSee('إنشاء الحساب');
});

test('a freelancer can register and lands on the dashboard', function () {
    $response = $this->post(route('register'), [
        'name' => 'سارة الحربي',
        'job_title' => 'مطورة مواقع',
        'email' => 'sara@example.com',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
    expect(User::firstWhere('email', 'sara@example.com'))->job_title->toBe('مطورة مواقع');
});

test('registration rejects a duplicate email and weak password', function () {
    User::factory()->create(['email' => 'taken@example.com']);

    $this->post(route('register'), [
        'name' => 'x',
        'email' => 'taken@example.com',
        'password' => 'short',
        'password_confirmation' => 'short',
    ])->assertSessionHasErrors(['email', 'password']);

    $this->assertGuest();
});

test('a freelancer can log in with valid credentials', function () {
    $user = User::factory()->create();

    $this->post(route('login'), ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('login fails with the wrong password', function () {
    $user = User::factory()->create();

    $this->post(route('login'), ['email' => $user->email, 'password' => 'wrong-password'])
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('login is rate limited after five failed attempts', function () {
    $user = User::factory()->create();

    foreach (range(1, 5) as $attempt) {
        $this->post(route('login'), ['email' => $user->email, 'password' => 'wrong']);
    }

    $this->post(route('login'), ['email' => $user->email, 'password' => 'password'])->assertTooManyRequests();
});

test('a freelancer can log out', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('logout'))
        ->assertRedirect(route('home'));

    $this->assertGuest();
});

test('guests are redirected from the dashboard to login', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});

test('signed-in users are redirected away from the login page', function () {
    $this->actingAs(User::factory()->create())->get(route('login'))->assertRedirect(route('dashboard'));
});
