<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Enums\ApiStatus;
use App\Enums\Messages;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (BaseApiException $e, $request) {
            $code = $e->getCode() ?: Response::HTTP_BAD_REQUEST;
            return response()->json([
                'status'      => ApiStatus::ERROR->value,
                'status_code' => $code,
                'message'     => $e->getMessage(),
            ], $code);
        });


        $this->renderable(function (Throwable $e, $request) {
            if ($e instanceof \Illuminate\Database\QueryException) {
                return response()->json([
                    'status'      => ApiStatus::ERROR->value,
                    'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message'     => Messages::DATABASE_ERROR->value,
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $code = $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = config('app.debug')
                ? $e->getMessage()
                : Messages::UNEXPECTED_ERROR->value;
            return response()->json([
                'status'      => ApiStatus::ERROR->value,
                'status_code' => $code,
                'message'     => $message,
            ], $code);
        });
    }
}
