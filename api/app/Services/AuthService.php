<?php namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\Messages;
use App\Exceptions\BaseApiException;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function __construct() {}

    public function login(array $credentials): array
    {
        if (!Auth::attempt($credentials)) {
            throw new BaseApiException(
                Messages::INVALID_CREDENTIALS->value, 
                Response::HTTP_UNAUTHORIZED
            );
        }
    
        $user = Auth::user();

        if ($user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return compact('user', 'token');
    }

    public function logout($user): void
    {
        $user->currentAccessToken()->delete();
    }
}
