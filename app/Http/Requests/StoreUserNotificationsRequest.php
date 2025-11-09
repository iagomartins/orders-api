<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserNotificationsRequest extends FormRequest
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
        // Check the URI path to determine which method is being called
        $uri = $this->path();
        $actionName = $this->route()->getActionName();
        
        // For getNotificationsByUser - only needs user_id
        if ($uri === 'api/v1/showUserNotifications' || 
            str_contains($actionName, 'getNotificationsByUser')) {
            return [
                'user_id' => 'required|integer|exists:users,id',
            ];
        }
        
        // For store (creating notifications) - needs user_id and message
        return [
            'user_id' => 'required|integer|exists:users,id',
            'message' => 'required|string|max:1000',
        ];
    }
}
