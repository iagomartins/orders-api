<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\Api\V1\UserController;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_users_can_be_controlled(): void
    {
        $user = new UserController();
        $this->assertInstanceOf(UserController::class, $user);
    }
}
