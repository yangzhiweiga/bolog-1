<?php

namespace App\Policies;

use App\Models\Status;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatusPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 删除微博授权
     *
     * @param User $user
     * @param Status $status
     * @return bool
     */
    public function destroy(User $user,Status $status)
    {
        return $user->id === $status->user_id;
    }
}
