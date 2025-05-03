<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Http\Resources\LoginResource;
use App\Enums\ApiStatus;
use App\Enums\Messages;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cookie;
class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $this->authService->login($request->validated());
        $resource = new LoginResource((object) $data);

        $cookie = Cookie::make(
            name:      'access_token',
            value:     $data['token'],
            minutes:   60,
            path:      '/',
            domain:    null,
            secure:    true,
            httpOnly:  true,
            raw:       false,
            sameSite:  'Strict'
        );

        return response()->json([
            'status'      => ApiStatus::SUCCESS->value,
            'status_code' => Response::HTTP_OK,
            'message'     => Messages::LOGIN_SUCCESSFUL->value,
            'data'        => $resource,
        ])->withCookie($cookie);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'status'      => ApiStatus::SUCCESS->value,
            'status_code' => Response::HTTP_OK,
            'message'     => Messages::LOGGED_OUT_SUCCESSFULLY->value,
        ]);
    }
}
