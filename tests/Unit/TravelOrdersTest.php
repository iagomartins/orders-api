<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\Api\V1\TravelOrdersController;

class TravelOrdersTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_orders_can_be_controlled(): void
    {
        $user = new TravelOrdersController();
        $this->assertInstanceOf(TravelOrdersController::class, $user);
    }
}
