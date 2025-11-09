<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserNotificationsRequest;
use App\Http\Resources\UserNotificationResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\UserNotifications;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UserNotificationsController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/api/v1/notifications",
     *     summary="Get all user notifications",
     *     tags={"User Notifications"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of notifications retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/UserNotification")),
     *             @OA\Property(property="message", type="string", example="Notifications retrieved successfully"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $notifications = UserNotifications::all();
            return $this->successResponse(
                UserNotificationResource::collection($notifications),
                'Notifications retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Error fetching notifications: ' . $e->getMessage());
            return $this->errorResponse('Failed to retrieve notifications', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/api/v1/notifications",
     *     summary="Create a new user notification",
     *     tags={"User Notifications"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "message"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="message", type="string", example="Your order has been confirmed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Notification created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/UserNotification"),
     *             @OA\Property(property="message", type="string", example="Notification created successfully"),
     *             @OA\Property(property="status_code", type="integer", example=201)
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function store(StoreUserNotificationsRequest $request): JsonResponse
    {
        try {
            $notification = UserNotifications::create($request->validated());
            return $this->createdResponse(
                new UserNotificationResource($notification),
                'Notification created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Error creating notification: ' . $e->getMessage());
            return $this->errorResponse('Failed to create notification', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/api/v1/notifications/{id}",
     *     summary="Get a specific user notification",
     *     tags={"User Notifications"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Notification ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notification retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/UserNotification"),
     *             @OA\Property(property="message", type="string", example="Notification retrieved successfully"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(response=404, description="Notification not found"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function show(UserNotifications $userNotification): JsonResponse
    {
        try {
            return $this->successResponse(
                new UserNotificationResource($userNotification),
                'Notification retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Error fetching notification: ' . $e->getMessage());
            return $this->errorResponse('Failed to retrieve notification', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/api/v1/notifications/{id}",
     *     summary="Delete a user notification",
     *     tags={"User Notifications"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Notification ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notification deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Notification deleted successfully"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(response=404, description="Notification not found"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function destroy(UserNotifications $userNotification): JsonResponse
    {
        try {
            $userNotification->delete();
            return $this->successResponse(
                null,
                'Notification deleted successfully'
            );
        } catch (\Exception $e) {
            Log::error('Error deleting notification: ' . $e->getMessage());
            return $this->errorResponse('Failed to delete notification', 500);
        }
    }

    /**
     * Get notifications by user ID.
     *
     * @OA\Post(
     *     path="/api/v1/showUserNotifications",
     *     summary="Get notifications by user ID",
     *     tags={"User Notifications"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id"},
     *             @OA\Property(property="user_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User notifications retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/UserNotification")),
     *             @OA\Property(property="message", type="string", example="User notifications retrieved successfully"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function getNotificationsByUser(StoreUserNotificationsRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $userId = $validated['user_id'] ?? null;

            if (!$userId) {
                return $this->errorResponse('User ID is required', 400);
            }

            $userNotifications = UserNotifications::where('user_id', $userId)->get();

            return $this->successResponse(
                UserNotificationResource::collection($userNotifications),
                'User notifications retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Error fetching user notifications: ' . $e->getMessage());
            return $this->errorResponse('Failed to retrieve user notifications', 500);
        }
    }
}
