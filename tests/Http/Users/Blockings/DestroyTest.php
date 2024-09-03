<?php

declare(strict_types=1);

use App\Models\User;

it('can unblock users', function () {
    $user = User::factory()->create();
    $blocker = User::factory()->create();
    $blocker->blocking()->attach($user);

    login($blocker)
        ->delete(route('blocking.destroy', $user))
        ->assertOk();

    expect($blocker->fresh()->blocking)
        ->toHaveLength(0);
});
