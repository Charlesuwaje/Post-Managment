<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine if the given post can be updated by the user.
     *
     * @param User $user
     * @param Post $post
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->name === RoleEnum::SUPER_ADMIN->value || $user->name === RoleEnum::ADMIN->value;
    }
    public function update(User $user, Post $post): bool
    {
        // return $user->id === $post->user_id;
        return $user->name === RoleEnum::SUPER_ADMIN->value || $user->name === RoleEnum::ADMIN->value;
    }

    /**
     * Determine if the given post can be deleted by the user.
     *
     * @param User $user
     * @param Post $post
     * @return bool
     */
    public function delete(User $user, Post $post): bool
    {
        // return $user->id === $post->user_id;
        return $user->name === RoleEnum::SUPER_ADMIN->value || $user->name === RoleEnum::ADMIN->value;
    }
}
