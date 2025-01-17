<?php

namespace App\Services;

use App\Enums\ActionEnum;
use App\Enums\MessageEnum;
use App\Models\Post;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;

class PostService
{
    // public function getAllPosts($userId)
    // {
    //     return Post::where('user_id', $userId)->get();
    // }

    public function getAllPosts($userId)
    {
        $posts = Post::where('user_id', $userId)->get();

        return [
            'status' => true,
            'message' => 'Posts retrieved successfully.',
            'data' => $posts,
        ];
    }


    public function createPost($data, $userId)
    {
        $post = Post::create(array_merge($data, ['user_id' => $userId]));

        app(ActivityLogService::class)->logActivity(
            $userId,
            $post->id,
            ActionEnum::CREATE->value,
            $post->toArray(), 
            MessageEnum::POST_CREATED->value 
        );

        return [
            'status' => true,
            'message' => 'Post created successfully.',
            'data' => $post,
        ];
    }



    public function getPost($postId, $userId)
    {
        $post = Post::where('id', $postId)->where('user_id', $userId)->first();

        if (!$post) {
            return [
                'status' => false,
                'message' => 'Post not found or unauthorized.',
                'data' => null,
            ];
        }

        return [
            'status' => true,
            'message' => 'Post retrieved successfully.',
            'data' => $post,
        ];
    }



    public function updatePost($postId, $data, $userId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return [
                'status' => false,
                'message' => 'Post not found.',
                'data' => null,
            ];
        }

        if ($post->user_id !== $userId) {
            return [
                'status' => false,
                'message' => 'Unauthorized to update this post.',
                'data' => null,
            ];
        }

        $post->update($data);

        // ActivityLog::create([
        //     'user_id' => $userId,
        //     'post_id' => $post->id,
        //     'action' => 'update',
        //     'data' => json_encode($data),
        //     'message' => 'Post updated successfully.',
        // ]);
        app(ActivityLogService::class)->logActivity(
            $userId,
            $post->id,
            ActionEnum::UPDATE->value,
            $post->toArray(), 
            MessageEnum::POST_UPDATED->value 
        );

        return [
            'status' => true,
            'message' => 'Post updated successfully.',
            'data' => $post,
        ];
    }

    public function deletePost($postId, $userId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return [
                'status' => false,
                'message' => 'Post not found.',
                'data' => null,
            ];
        }

        // ActivityLog::create([
        //     'user_id' => $userId,
        //     'post_id' => $post->id,
        //     'action' => 'delete',
        //     'data' => json_encode($post->toArray()),
        //     'message' => 'Post deleted successfully.',
        // ]);
        app(ActivityLogService::class)->logActivity(
            $userId,
            $post->id,
            ActionEnum::DELETE->value,
            $post->toArray(), 
            MessageEnum::POST_DELETED->value 
        );

        $post->delete();

        return [
            'status' => true,
            'message' => 'Post deleted successfully.',
            'data' => $post,
        ];
    }
}
