<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\UsersFactory;

class Users extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'password'];
}
