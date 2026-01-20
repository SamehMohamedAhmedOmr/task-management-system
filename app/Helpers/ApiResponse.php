<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use App\Constants\HttpStatus;

class ApiResponse
{
    public static function format($code, $body = [], $message = '', $pagination = null)
    {
        $message = $message ?? '';

        $response = [
            'message' => $message,
            'body' => $body ?? null,
        ];

        if ($pagination !== null) {
            $response['pagination'] = $pagination;
        }

        return Response::json($response, $code);
    }

    /**
     * Success Response.
     *
     * @param int $code
     * @param mixed $data
     * @param string $message
     * @param mixed $pagination
     * @return JsonResponse
     */
    public static function success(int $code = HttpStatus::OK, mixed $data = null, string $message = 'Success', mixed $pagination = null): JsonResponse
    {
        return self::format($code, $data, $message, $pagination);
    }

    /**
     * Error Response.
     *
     * @param int $code
     * @param mixed $errors
     * @param string $message
     * @return JsonResponse
     */
    public static function error(int $code = HttpStatus::BAD_REQUEST, mixed $errors = null, string $message = 'Error'): JsonResponse
    {
        return self::format($code, $errors, $message);
    }

    /**
     * Validation Error Response.
     *
     * @param mixed $errors
     * @return JsonResponse
     */
    public static function validationError(mixed $errors): JsonResponse
    {
        return self::error(HttpStatus::UNPROCESSABLE_ENTITY, $errors, 'Validation Error');
    }

    /**
     * Unauthorized Response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return self::error(HttpStatus::UNAUTHORIZED, null, $message);
    }

    /**
     * Not Found Response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return self::error(HttpStatus::NOT_FOUND, null, $message);
    }

    /**
     * Created Response.
     *
     * @param mixed $data
     * @param string $message
     * @return JsonResponse
     */
    public static function created(mixed $data, string $message = 'Resource created successfully'): JsonResponse
    {
        return self::success(HttpStatus::CREATED, $data, $message);
    }
}
