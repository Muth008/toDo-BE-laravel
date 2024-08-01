<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *      name="Authentication",
 *      description="API Endpoints for user authentication"
 * )
 */
class RegisterUserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/ApiResponse"),
     *                 @OA\Schema(
     *                     properties={
     *                         @OA\Property(
     *                             property="data",
     *                             ref="#/components/schemas/User"
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
    public function store(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return $this->sendResponse(
            new UserResource($user),
            'User registered successfully',
            201
        );
    }
}
