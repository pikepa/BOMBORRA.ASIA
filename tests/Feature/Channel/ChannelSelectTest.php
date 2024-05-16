<?php

use App\Livewire\Forms\ChannelSelect;
use App\Models\Channel;
use Livewire\Livewire;

test('when an active channel is created it appears on the select list', function () {
    $channelA = Channel::factory()->create(['status' => 1,
        'sort' => 2, ]);
    $channelB = Channel::factory()->create(['status' => 1,
        'sort' => 1, ]);
    Livewire::test(ChannelSelect::class)
        ->assertOk()
        ->assertSee($channelA->name)
        ->assertViewHas('queryChannels')
        ->assertSeeInOrder([$channelB->name, $channelA->name]);
});
test('when a channel is created as inactive it does not appear on the select list', function () {
    $channel = Channel::factory()->create(['status' => 0]);

    Livewire::test(ChannelSelect::class)
        ->assertOk()
        ->assertDontSee($channel->name);
});
