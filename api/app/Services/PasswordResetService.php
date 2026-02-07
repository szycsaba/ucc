<?php
namespace App\Services;

use App\DTO\ServiceResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class PasswordResetService
{
    public function sendResetLink(array $data): ServiceResponse
    {
        $status = Password::sendResetLink(['email' => $data['email']]);

        return new ServiceResponse(
            success: true,
            message: 'If the email exists, a password reset link has been sent.',
            status: 200
        );
    }

    public function resetPassword(array $data): ServiceResponse
    {
        $status = Password::reset(
            [
                'email' => $data['email'],
                'password' => $data['password'],
                'password_confirmation' => $data['password_confirmation'],
                'token' => $data['token'],
            ],
            function ($user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return new ServiceResponse(
                success: false,
                message: $status,
                status: 422
            );
        }

        return new ServiceResponse(
            success: true,
            message: 'Password has been reset successfully.',
            status: 200
        );
    }
}
