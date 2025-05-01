<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\Api\V1\UserNotificationsController;

class NotificationsTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_notifications_can_be_controlled(): void
    {
        $user = new UserNotificationsController();
        $this->assertInstanceOf(UserNotificationsController::class, $user);
    }
}
