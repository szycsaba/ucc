<?php

namespace App\Services;

use App\DTO\ServiceResponse;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $repo
    ) {}

    public function login(array $credentials, Session $session, bool $remember = false): ServiceResponse
    {
        if (! Auth::attempt($credentials, $remember)) {
            return new ServiceResponse(
                success: false,
                message: 'Invalid credentials',
                status: 422
            );
        }

        $session->regenerate();

        return new ServiceResponse(
            success: true,
            message: 'Login successful',
            data: (new UserResource(Auth::user()))->resolve(), // nem kell request()
            status: 200
        );
    }

    public function logout(Session $session): ServiceResponse
    {
        Auth::guard('web')->logout();

        $session->invalidate();
        $session->regenerateToken();

        return new ServiceResponse(
            success: true,
            message: 'Logged out',
            status: 200
        );
    }
}
