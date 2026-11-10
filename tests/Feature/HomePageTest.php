<?php

use App\Models\User;

test('the home page describes the services and invites guests to sign up', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('إدارة العملاء')
        ->assertSee('عروض الأسعار')
        ->assertSee('فواتير ضريبية')
        ->assertSee('صفحة الروابط')
        ->assertSee(route('register'))
        ->assertSee(route('login'));
});

test('the home page links signed-in users to their dashboard', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('home'))
        ->assertOk()
        ->assertSee('لوحة التحكم')
        ->assertDontSee('ابدأ مجانًا');
});
