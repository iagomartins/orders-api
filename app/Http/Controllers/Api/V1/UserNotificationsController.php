<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserNotificationsRequest;
use App\Models\UserNotifications;
use Request;

class UserNotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserNotifications::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserNotificationsRequest $request)
    {
        $notification = UserNotifications::create($request->all());

        return [
            'message' => 'Notification created successfully!',
            'order' => $notification
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(UserNotifications $userNotifications)
    {
        return $userNotifications;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserNotifications $userNotifications)
    {
        $userNotifications->delete();
    }

    public function getNotificationsByUser(Request $request) {
        $userNotifications = UserNotifications::where('user_id','=', $request->user_id)->get();
        return $userNotifications;
    }
}
