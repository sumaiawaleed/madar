<?php

use App\Models\Link;
use App\Models\User;

test('a freelancer can add links that appear on their public page', function () {
    $user = User::factory()->create(['handle' => 'ahmad', 'job_title' => 'مصمم']);

    $this->actingAs($user)->post(route('links.store'), ['title' => 'معرض أعمالي', 'url' => 'behance.net/ahmad'])
        ->assertRedirect(route('links.index'));

    $this->get(route('link-page.show', 'ahmad'))
        ->assertOk()
        ->assertSee('معرض أعمالي')
        ->assertSee('https://behance.net/ahmad')
        ->assertSee('مصمم');
});

test('an unknown handle returns not found', function () {
    $this->get(route('link-page.show', 'nobody'))->assertNotFound();
});

test('moving a link up swaps it with the one above', function () {
    $user = User::factory()->create();
    $first = Link::factory()->for($user)->create(['title' => 'الأول', 'position' => 1]);
    $second = Link::factory()->for($user)->create(['title' => 'الثاني', 'position' => 2]);

    $this->actingAs($user)->patch(route('links.position.update', $second))->assertRedirect(route('links.index'));

    expect($user->links()->pluck('title')->all())->toBe(['الثاني', 'الأول']);
});

test('a link url must look like a web address', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('links.store'), ['title' => 'x', 'url' => 'not a url'])
        ->assertSessionHasErrors('url');
});

test('a freelancer cannot edit another freelancer link', function () {
    $link = Link::factory()->create();

    $this->actingAs(User::factory()->create())->get(route('links.edit', $link))->assertForbidden();
});
