<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *     title="ToDo API",
 *     version="1.0.0",
 *     description="API Documentation for ToDo Application"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Use a Bearer token to authorize requests"
 * )
 * @OA\Schema(
 *     schema="ApiResponse",
 *     type="object",
 *     properties={
 *         @OA\Property(
 *             property="success",
 *             type="boolean"
 *         ),
 *         @OA\Property(
 *             property="data",
 *             type="object"
 *         ),
 *         @OA\Property(
 *             property="message",
 *             type="string"
 *         )
 *     }
 * )
 */
abstract class Controller
{
    /**
     * Send a JSON response.
     */
    protected function sendJsonResponse(bool $success, $data, string $message = null, int $code = 200): JsonResponse
    {
        $response = [
            'success' => $success,
            'data'    => $data,
        ];

        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response, $code);
    }

    /**
     * Success response method.
     */
    public function sendResponse($result, string $message = null, int $code = 200): JsonResponse
    {
        return $this->sendJsonResponse(true, $result, $message, $code);
    }

    /**
     * Return error response.
     */
    public function sendError($error, string $message = null, int $code = 404): JsonResponse
    {
        return $this->sendJsonResponse(false, $error, $message, $code);
    }
}
