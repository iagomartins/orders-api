<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\UserNotificationsFactory;

class UserNotifications extends Model
{
    /** @use HasFactory<\Database\Factories\UserNotificationsFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'message'];
}
