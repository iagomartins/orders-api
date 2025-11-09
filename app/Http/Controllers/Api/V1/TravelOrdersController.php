<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTravelOrdersRequest;
use App\Http\Requests\UpdateTravelOrdersRequest;
use App\Http\Resources\TravelOrderResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\TravelOrders;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TravelOrdersController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/api/v1/orders",
     *     summary="Get all travel orders",
     *     tags={"Travel Orders"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of travel orders retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/TravelOrder")),
     *             @OA\Property(property="message", type="string", example="Travel orders retrieved successfully"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $orders = TravelOrders::all();
            return $this->successResponse(
                TravelOrderResource::collection($orders),
                'Travel orders retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Error fetching travel orders: ' . $e->getMessage());
            return $this->errorResponse('Failed to retrieve travel orders', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/api/v1/orders",
     *     summary="Create a new travel order",
     *     tags={"Travel Orders"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"customer_name", "destiny", "start_date", "return_date", "status", "user_id"},
     *             @OA\Property(property="customer_name", type="string", example="John Doe"),
     *             @OA\Property(property="destiny", type="string", example="Paris"),
     *             @OA\Property(property="start_date", type="string", format="date", example="2024-06-01"),
     *             @OA\Property(property="return_date", type="string", format="date", example="2024-06-15"),
     *             @OA\Property(property="status", type="string", example="Pending"),
     *             @OA\Property(property="user_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Travel order created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/TravelOrder"),
     *             @OA\Property(property="message", type="string", example="Travel order created successfully"),
     *             @OA\Property(property="status_code", type="integer", example=201)
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function store(StoreTravelOrdersRequest $request): JsonResponse
    {
        try {
            $order = TravelOrders::create($request->validated());
            return $this->createdResponse(
                new TravelOrderResource($order),
                'Travel order created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Error creating travel order: ' . $e->getMessage());
            return $this->errorResponse('Failed to create travel order', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/api/v1/orders/{id}",
     *     summary="Get a specific travel order",
     *     tags={"Travel Orders"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Travel Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Travel order retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/TravelOrder"),
     *             @OA\Property(property="message", type="string", example="Travel order retrieved successfully"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(response=404, description="Travel order not found"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function show(TravelOrders $order): JsonResponse
    {
        try {
            return $this->successResponse(
                new TravelOrderResource($order),
                'Travel order retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Error fetching travel order: ' . $e->getMessage());
            return $this->errorResponse('Failed to retrieve travel order', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/api/v1/orders/{id}",
     *     summary="Update a travel order",
     *     tags={"Travel Orders"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Travel Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="customer_name", type="string", example="John Doe"),
     *             @OA\Property(property="destiny", type="string", example="Paris"),
     *             @OA\Property(property="start_date", type="string", format="date", example="2024-06-01"),
     *             @OA\Property(property="return_date", type="string", format="date", example="2024-06-15"),
     *             @OA\Property(property="status", type="string", example="Cancelled"),
     *             @OA\Property(property="user_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Travel order updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/TravelOrder"),
     *             @OA\Property(property="message", type="string", example="Travel order updated successfully"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Cannot cancel order with less than 30 days until travel",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You cannot cancel an order with less than 30 days until the travel"),
     *             @OA\Property(property="status_code", type="integer", example=400)
     *         )
     *     ),
     *     @OA\Response(response=404, description="Travel order not found"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function update(UpdateTravelOrdersRequest $request, TravelOrders $order): JsonResponse
    {
        try {
            $attributes = $request->validated();

            // Check if trying to cancel the order
            if (isset($attributes['status']) && $attributes['status'] === 'Cancelled') {
                $minDate = now()->addDays(30)->format('Y-m-d');
                $startDate = $order->start_date;

                if ($startDate < $minDate) {
                    return $this->errorResponse(
                        'You cannot cancel an order with less than 30 days until the travel',
                        400
                    );
                }
            }

            $order->update($attributes);

            return $this->successResponse(
                new TravelOrderResource($order->fresh()),
                'Travel order updated successfully'
            );
        } catch (\Exception $e) {
            Log::error('Error updating travel order: ' . $e->getMessage());
            return $this->errorResponse('Failed to update travel order', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/api/v1/orders/{id}",
     *     summary="Delete a travel order",
     *     tags={"Travel Orders"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Travel Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Travel order deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Travel order deleted successfully"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(response=404, description="Travel order not found"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function destroy(TravelOrders $order): JsonResponse
    {
        try {
            $order->delete();
            return $this->successResponse(
                null,
                'Travel order deleted successfully'
            );
        } catch (\Exception $e) {
            Log::error('Error deleting travel order: ' . $e->getMessage());
            return $this->errorResponse('Failed to delete travel order', 500);
        }
    }

    /**
     * Filter travel orders by destination and date range.
     *
     * @OA\Post(
     *     path="/api/v1/filterOrders",
     *     summary="Filter travel orders by destination and date range",
     *     tags={"Travel Orders"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="destination", type="string", example="Paris", nullable=true),
     *             @OA\Property(property="start_date", type="string", format="date", example="2024-01-01", nullable=true),
     *             @OA\Property(property="end_date", type="string", format="date", example="2024-12-31", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Filtered travel orders retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/TravelOrder")),
     *             @OA\Property(property="message", type="string", example="Filtered travel orders retrieved successfully"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function showOrdersByFilters(StoreTravelOrdersRequest $request): JsonResponse
    {
        try {
            $filters = $request->validated();
            $query = TravelOrders::query();

            if (!empty($filters['destination'])) {
                $query->where('destiny', $filters['destination']);
            }

            if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                $query->whereBetween('created_at', [
                    $filters['start_date'],
                    $filters['end_date']
                ]);
            }

            $travelOrders = $query->get();

            return $this->successResponse(
                TravelOrderResource::collection($travelOrders),
                'Filtered travel orders retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Error filtering travel orders: ' . $e->getMessage());
            return $this->errorResponse('Failed to filter travel orders', 500);
        }
    }

    /**
     * Get travel orders by user ID.
     *
     * @OA\Post(
     *     path="/api/v1/ordersByUser",
     *     summary="Get travel orders by user ID",
     *     tags={"Travel Orders"},
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
     *         description="User travel orders retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/TravelOrder")),
     *             @OA\Property(property="message", type="string", example="User travel orders retrieved successfully"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function showOrdersByUser(StoreTravelOrdersRequest $request): JsonResponse
    {
        try {
            $filters = $request->validated();
            $userId = $filters['user_id'] ?? null;

            if (!$userId) {
                return $this->errorResponse('User ID is required', 400);
            }

            $travelOrders = TravelOrders::where('user_id', $userId)->get();

            return $this->successResponse(
                TravelOrderResource::collection($travelOrders),
                'User travel orders retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Error fetching user travel orders: ' . $e->getMessage());
            return $this->errorResponse('Failed to retrieve user travel orders', 500);
        }
    }
}
