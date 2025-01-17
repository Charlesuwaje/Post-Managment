<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    public function __construct(public readonly RoleService $roleService)
    {
    }

    public function index()
    {
        $response = $this->roleService->getAllRole(auth()->id());

        return response()->json([
            'status' => true,
            'message' => $response['message'],
            'data' => $response['data'],
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $response = $this->roleService->createRole($validated, auth()->id());

        return response()->json([
            'status' => true,
            'message' => $response['message'],
            'data' => $response['data'],
        ], Response::HTTP_CREATED);
    }



    public function show($id)
    {
        $response = $this->roleService->getRoleById($id, auth()->id());

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


    public function update(Request $request, $id)
    {
        $response = $this->roleService->updateRole($id, $request->all(), auth()->id());

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
        $response = $this->roleService->deleteRole($id, auth()->id());

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
