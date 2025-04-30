<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StoreTravelOrdersRequest;
use App\Http\Requests\UpdateTravelOrdersRequest;
use App\Models\TravelOrders;
use App\Http\Controllers\Controller;

class TravelOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TravelOrders::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTravelOrdersRequest $request)
    {
        $order = TravelOrders::create($request->all());

        return [
            'message' => 'Order created successfully!',
            'order' => $order
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(TravelOrders $order)
    {
        return $order;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTravelOrdersRequest $request, TravelOrders $order)
    {
        $attributes = $request->all();

        $canUpdateTravelOrder = true;

        if ($attributes['status'] == 'Cancelled') {
            $date = date('Y-m-d', strtotime('+30 days'));
            $canUpdateTravelOrder = $order['start_date'] < $date ? false : true;
        }

        if ($canUpdateTravelOrder) {
            $order->update($request->all());

            return [
                'message' => 'Order updated!',
                'status' => 'success'
            ];
        } else {
            return [
                'message' => 'You can not cancel an order with less than 30 days until the travel!',
                'status' => 'error'
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TravelOrders $travelOrders)
    {
        $travelOrders->delete();

        return ['message' => 'Order canceled!'];
    }

    public function showOrdersByFilters(StoreTravelOrdersRequest $request) {
        $filters = $request->all();

        // $destination = $filters['destination'];
        // $start_date = $filters['start_date'];
        // $end_date = $filters['end_date'];

        $travel_orders = [];

        if ($filters['destination'] == '') {
            $travel_orders = TravelOrders::whereBetween('created_at', [date_create($filters['start_date']), date_create($filters['end_date'])])->get();
        } else if ($filters['start_date'] == '') {
            $travel_orders = TravelOrders::where('destiny', '=',  $filters['destination']);
        } else {
            $travel_orders = TravelOrders::where('destiny', '=',  $filters['destination'])->whereBetween('created_at', [date_create($filters['start_date']), date_create($filters['end_date'])])->get();
        }

        return $travel_orders;
    }
}
