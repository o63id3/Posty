<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface NewModelAction
{
    /**
     * Handle the action.
     *
     * @param  array<int, int>  $ids
     */
    public function handle(): Model;
}
