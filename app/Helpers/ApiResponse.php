<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class ApiResponse
{
    /**
     * Success Response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public static function success(mixed $data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return Response::json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => null,
        ], $code);
    }

    /**
     * Error Response.
     *
     * @param string $message
     * @param int $code
     * @param mixed|null $errors
     * @return JsonResponse
     */
    public static function error(string $message = 'Error', int $code = 400, mixed $errors = null): JsonResponse
    {
        return Response::json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors,
        ], $code);
    }

    /**
     * Validation Error Response.
     *
     * @param mixed $errors
     * @return JsonResponse
     */
    public static function validationError(mixed $errors): JsonResponse
    {
        return self::error('Validation Error', 422, $errors);
    }

    /**
     * Unauthorized Response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return self::error($message, 401);
    }

    /**
     * Not Found Response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return self::error($message, 404);
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
        return self::success($data, $message, 201);
    }
}
