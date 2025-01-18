<?php

namespace App\Services;

use App\Enums\ActionEnum;
use App\Enums\MessageEnum;
use App\Models\Post;
use App\Models\ActivityLog;
use App\Models\Role;
use Illuminate\Support\Facades\Log;

class RoleService
{
    // public function getAllPosts($userId)
    // {
    //     return Post::where('user_id', $userId)->get();
    // }

    public function getAllRole($userId)
    {
        $role = Role::where('user_id', $userId)->get();

        return [
            'status' => true,
            'message' => 'Role retrieved successfully.',
            'data' => $role,
        ];
    }


    public function createRole($data, $userId)
    {
        $role = Role::create(array_merge($data, ['user_id' => $userId]));

        app(ActivityLogService::class)->logActivity(
            $userId,
            $role->id,
            ActionEnum::CREATE->value,
            $role->toArray(),
            MessageEnum::ROLE_CREATED->value
        );

        return [
            'status' => true,
            'message' => 'Role created successfully.',
            'data' => $role,
        ];
    }




    public function getRoleById($postId, $userId)
    {
        $role = Role::where('id', $postId)->where('user_id', $userId)->first();

        if (!$role) {
            return [
                'status' => false,
                'message' => 'Role not found or unauthorized.',
                'data' => null,
            ];
        }

        return [
            'status' => true,
            'message' => 'role retrieved successfully.',
            'data' => $role,
        ];
    }



    public function updateRole($roleId, $data, $userId)
    {
        $role = Post::find($roleId);

        if (!$role) {
            return [
                'status' => false,
                'message' => 'role not found.',
                'data' => null,
            ];
        }

        if ($role->user_id !== $userId) {
            return [
                'status' => false,
                'message' => 'Unauthorized to update this role.',
                'data' => null,
            ];
        }

        $role->update($data);

        app(ActivityLogService::class)->logActivity(
            $userId,
            $role->id,
            ActionEnum::UPDATE->value,
            $role->toArray(),
            MessageEnum::ROLE_UPDATED->value
        );

        return [
            'status' => true,
            'message' => 'Role updated successfully.',
            'data' => $role,
        ];
    }

    public function deleteRole($roleId, $userId)
    {
        $role = Post::find($roleId);

        if (!$role) {
            return [
                'status' => false,
                'message' => 'Post not found.',
                'data' => null,
            ];
        }
        app(ActivityLogService::class)->logActivity(
            $userId,
            $role->id,
            ActionEnum::DELETE->value,
            $role->toArray(),
            MessageEnum::ROLE_DELETED->value
        );

        $role->delete();

        return [
            'status' => true,
            'message' => 'Post deleted successfully.',
            'data' => $role
        ];
    }
}
