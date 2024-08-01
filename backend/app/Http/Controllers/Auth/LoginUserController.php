<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *      name="Authentication",
 *      description="API Endpoints for user authentication"
 * )
 */
class LoginUserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Login a user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/ApiResponse"),
     *                 @OA\Schema(
     *                     properties={
     *                         @OA\Property(
     *                              property="data", type="object",
     *                              @OA\Property(
     *                                  property="token",
     *                                  type="string",
     *                                  description="Authentication token"
     *                              ),
     *                             @OA\Property(
     *                                 property="role",
     *                                 type="string",
     *                                 description="User role"
     *                            )
     *                         )
     *                     }
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        // delete all previous tokens
        $request->user()->tokens()->delete();

        // create new token
        $token = $request->user()->createToken('AUTH_TOKEN');

        return $this->sendResponse([
            'token' => $token->plainTextToken,
            'role' => $request->user()->role
        ]);
    }
}
