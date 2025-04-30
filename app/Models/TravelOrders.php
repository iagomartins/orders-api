<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\TravelOrdersFactory;

class TravelOrders extends Model
{
    use HasFactory;

    protected $fillable = ['customer_name', 'destiny', 'start_date', 'return_date', 'status'];
}
