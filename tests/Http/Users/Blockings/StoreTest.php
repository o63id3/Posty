<?php

declare(strict_types=1);

use App\Models\User;

it('can block users', function () {
    $user = User::factory()->create();

    login($blocker = User::factory()->create())
        ->post(route('blocking.store', $user))
        ->assertOk();

    expect($blocker->fresh()->blocking)
        ->toHaveLength(1);
});
