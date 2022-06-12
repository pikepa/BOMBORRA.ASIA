<?php

use App\Models\Post;
use Livewire\Livewire;
use App\Http\Livewire\Posts\ShowPost;
use App\Http\Livewire\Posts\ManagePosts;

test('an authorised user sees the Manage Posts page', function () {
    $this->signIn();
    Livewire::test(ManagePosts::class)->assertSee('Posts')
    ->assertSee('A list of all the posts in your account.');

});

test('A guest can view a published post', function () {
    $post = Post::factory()->create();

   Livewire::test(ShowPost::class,['slug' => $post->slug])
       ->assertStatus(200)
        ->assertSee('Home')
        ->assertSee('Bomborra')
        ->assertSee($post->title)
        ->assertSee($post->body);
});

test('An authorised user can see a list of all posts', function () {
    
    $this->signIn();

    $post1 = Post::factory()->create();
    $post2 = Post::factory()->create();

    $this->get('/posts/')
        ->assertSee($post1->title)
        ->assertSee($post1->author_id)
        ->assertSee($post1->published_at)
        ->assertSee('Home')
        ->assertSee('Bomborra')
        ->assertSee($post2->title)
        ->assertSee($post2->author_id)
        ->assertSee($post2->published_at);
});



