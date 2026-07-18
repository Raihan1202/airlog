<?php

namespace App\Policies;

use App\Models\Logbook;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogbookPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the logbook.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Logbook  $logbook
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Logbook $logbook)
    {
        // Cek apakah user adalah yang membuat logbook
        return $user->id === $logbook->created_by;
    }

    /**
     * Determine whether the user can approve the logbook.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Logbook  $logbook
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function approve(User $user, Logbook $logbook)
    {
        // Cek apakah user memiliki access_level 1 atau 2
        return in_array($user->access_level, [1, 2]);
    }
}