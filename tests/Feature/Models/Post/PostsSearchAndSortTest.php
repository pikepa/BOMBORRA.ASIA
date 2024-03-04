<?php

use App\Livewire\Posts\ManagePosts;
use App\Models\Category;
use App\Models\Channel;
use App\Models\Post;
use Livewire\Livewire;

beforeEach(function () {
    $this->category = Category::factory()->create();
    $this->channel = Channel::factory()->create();
});

test('An authorised User can search for a post in the dashboard', function () {
    $this->signIn();
    $post1 = Post::factory()->create();
    $post = Post::factory()->create(['title' => 'My Title']);

    Livewire::test(ManagePosts::class)
        ->set('search', 'title')
        ->assertSee($post->title)
        ->assertDontSee($post1->title);
});

test('An authorised User sees no Post found when too many chars in the search', function () {
    $this->signIn();
    $post1 = Post::factory()->create();
    $post = Post::factory()->create(['title' => 'My Title']);

    Livewire::test(ManagePosts::class)
        ->set('search', 'asdasdasdasdadasdadasdasdasdasdasdadadad')
        ->assertSee('No Posts found')
        ->assertDontSee($post->title)
        ->assertDontSee($post1->title);
});
