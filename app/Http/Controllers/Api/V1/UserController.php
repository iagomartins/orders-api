<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());
        return [
            'message'=> 'User created successfully!'
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUserRequest $request, User $users)
    {
        $user = $users->update($request->all());
        return [
            'message'=> 'User updated successfully!'
        ];
    }

    public function createAccessToken(StoreUserRequest $request) {
        $data = $request->all();
        $user= User::where('email', '=', $data['email'])->first();

        if ($user['name'] == 'Admin' && password_verify($data['password'], $user['password'])) {
            return $user->createToken('token')->plainTextToken;
        }
    }

    public function Login(StoreUserRequest $request) {
        $data = $request->all();
        $user= User::where('email', '=', $data['email'])->first();

        if (password_verify($data['password'], $user['password'])) {
            return [
                'message'=> 'Login successful!',
                'user' => $user,
            ];
        }
    }
}
