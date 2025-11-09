<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/api/v1/users",
     *     summary="Get all users",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of users retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/User")),
     *             @OA\Property(property="message", type="string", example="Users retrieved successfully"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $users = User::all();
            return $this->successResponse(
                UserResource::collection($users),
                'Users retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());
            return $this->errorResponse('Failed to retrieve users', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/api/v1/users",
     *     summary="Create a new user",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/User"),
     *             @OA\Property(property="message", type="string", example="User created successfully"),
     *             @OA\Property(property="status_code", type="integer", example=201)
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);
            return $this->createdResponse(
                new UserResource($user),
                'User created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return $this->errorResponse('Failed to create user', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/api/v1/users/{id}",
     *     summary="Update a user",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/User"),
     *             @OA\Property(property="message", type="string", example="User updated successfully"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function update(StoreUserRequest $request, User $user): JsonResponse
    {
        try {
            $data = $request->validated();
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }
            $user->update($data);
            return $this->successResponse(
                new UserResource($user->fresh()),
                'User updated successfully'
            );
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return $this->errorResponse('Failed to update user', 500);
        }
    }

    /**
     * Create an access token for admin authentication.
     *
     * @OA\Post(
     *     path="/api/authenticate",
     *     summary="Authenticate admin and get access token",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object", @OA\Property(property="token", type="string")),
     *             @OA\Property(property="message", type="string", example="Token created successfully"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(response=401, description="Invalid credentials or not an admin")
     * )
     */
    public function createAccessToken(StoreUserRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $user = User::where('email', $data['email'])->first();

            if (!$user) {
                throw new UnauthorizedException('Invalid credentials');
            }

            if (!Hash::check($data['password'], $user->password)) {
                throw new UnauthorizedException('Invalid credentials');
            }

            if ($user->name !== 'Admin') {
                throw new UnauthorizedException('Access denied. Admin privileges required.');
            }

            $token = $user->createToken('token')->plainTextToken;

            return $this->successResponse(
                ['token' => $token],
                'Token created successfully'
            );
        } catch (UnauthorizedException $e) {
            return $this->unauthorizedResponse($e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error creating access token: ' . $e->getMessage());
            return $this->errorResponse('Failed to create access token', 500);
        }
    }

    /**
     * Login user.
     *
     * @OA\Post(
     *     path="/api/v1/userLogin",
     *     summary="Login user",
     *     tags={"Authentication"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object", @OA\Property(property="user", ref="#/components/schemas/User")),
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(response=401, description="Invalid credentials"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function login(StoreUserRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $user = User::where('email', $data['email'])->first();

            if (!$user) {
                throw new UnauthorizedException('Invalid credentials');
            }

            if (!Hash::check($data['password'], $user->password)) {
                throw new UnauthorizedException('Invalid credentials');
            }

            return $this->successResponse(
                ['user' => new UserResource($user)],
                'Login successful'
            );
        } catch (UnauthorizedException $e) {
            return $this->unauthorizedResponse($e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error during login: ' . $e->getMessage());
            return $this->errorResponse('Failed to login', 500);
        }
    }
}
