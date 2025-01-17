<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function create(User $user): bool
    {
        return $user->name === RoleEnum::SUPER_ADMIN->value || $user->name === RoleEnum::ADMIN->value;
    }

}
