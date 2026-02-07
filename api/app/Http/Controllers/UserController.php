<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function login(LoginRequest $request, UserService $service): JsonResponse
    {
        $response = $service->login($request->validated(), $request->session());

        return response()->json($response->toArray(), $response->status);
    }

    public function logout(Request $request, UserService $service): JsonResponse
    {
        $response = $service->logout($request->session());

        return response()->json($response->toArray(), $response->status);
    }
}
