<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    use AuthorizesRequests;
    public function __construct(public readonly PostService $postService) {}

    // public function index()
    // {
    //     $posts = $this->postService->getAllPosts(auth()->id());
    //     return response()->json($posts);
    // }

    public function index()
    {
        $response = $this->postService->getAllPosts(auth()->id());

        return response()->json([
            'status' => true,
            'message' => $response['message'],
            'data' => $response['data'],
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        $response = $this->postService->createPost($validated, auth()->id());

        return response()->json([
            'status' => true,
            'message' => $response['message'],
            'data' => $response['data'],
        ], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $response = $this->postService->getPost($id, auth()->id());

        if (!$response['status']) {
            return response()->json([
                'status' => false,
                'message' => $response['message'],
            ], Response::HTTP_FORBIDDEN);
        }

        return response()->json([
            'status' => true,
            'message' => $response['message'],
            'data' => $response['data'],
        ], Response::HTTP_OK);
    }


    // public function update(Request $request, $id)
    // {
    //     $this->authorize('update', Post::class);

    //     $response = $this->postService->updatePost($id, $request->all(), auth()->id());

    //     if (!$response['status']) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => $response['message'],
    //         ], Response::HTTP_BAD_REQUEST);
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => $response['message'],
    //         'data' => $response['data'],
    //     ], Response::HTTP_OK);
    // }
    public function update(Request $request, $id)
    {
        $response = $this->postService->updatePost($id, $request->all(), auth()->id());

        if (!$response['status']) {
            return response()->json([
                'status' => false,
                'message' => $response['message'],
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'status' => true,
            'message' => $response['message'],
            'data' => $response['data'],
        ], Response::HTTP_OK);
    }



    public function destroy($id)
    {

        $response = $this->postService->deletePost($id, auth()->id());

        if (!$response['status']) {
            return response()->json([
                'status' => false,
                'message' => $response['message'],
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'status' => true,
            'message' => $response['message'],
            'data' => $response['data'],
        ], Response::HTTP_OK);
    }
}
