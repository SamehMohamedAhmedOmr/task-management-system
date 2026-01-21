<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Task Management System API",
 *     version="1.0.0",
 *     description="API documentation for Task Management System",
 *     @OA\Contact(
 *         email="support@taskmanagement.com"
 *     )
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
abstract class Controller
{
    //
}
