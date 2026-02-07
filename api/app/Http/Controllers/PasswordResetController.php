<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\PasswordResetService;

class PasswordResetController extends Controller
{
    public function sendResetLink(ForgotPasswordRequest $request, PasswordResetService $service)
    {
        $resp = $service->sendResetLink($request->validated());
        return response()->json($resp->toArray(), $resp->status);
    }

    public function resetPassword(ResetPasswordRequest $request, PasswordResetService $service)
    {
        $resp = $service->resetPassword($request->validated());
        return response()->json($resp->toArray(), $resp->status);
    }
}
