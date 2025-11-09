<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Check the URI path to determine if this is an authentication route
        $uri = $this->path();
        $routeName = $this->route()->getName();
        $actionName = $this->route()->getActionName();
        
        // Check if this is for authentication (createAccessToken or login)
        if ($uri === 'api/authenticate' || 
            $uri === 'api/v1/userLogin' || 
            $routeName === 'userLogin' ||
            str_contains($actionName, 'createAccessToken') ||
            str_contains($actionName, '@login')) {
            return [
                'email' => 'required|email',
                'password' => 'required|string',
            ];
        }

        // For user creation/update
        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email' . ($this->isMethod('PUT') || $this->isMethod('PATCH') ? ',' . $this->route('user')?->id : ''),
            'password' => 'sometimes|required|string|min:8',
        ];
    }
}
