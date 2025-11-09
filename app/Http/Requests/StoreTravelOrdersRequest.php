<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTravelOrdersRequest extends FormRequest
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
        $routeName = $this->route()->getName();
        $actionName = $this->route()->getActionName();
        
        // For showOrdersByUser - only needs user_id
        if ($uri === 'api/v1/ordersByUser' || 
            str_contains($actionName, 'showOrdersByUser')) {
            return [
                'user_id' => 'required|integer|exists:users,id',
            ];
        }
        
        // For showOrdersByFilters - optional filters
        if ($uri === 'api/v1/filterOrders' || 
            str_contains($actionName, 'showOrdersByFilters')) {
            return [
                'destination' => 'nullable|string',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ];
        }
        
        // For store (creating orders) - all fields required
        return [
            'customer_name' => 'required|string|max:255',
            'destiny' => 'required|string|max:255',
            'start_date' => 'required|date',
            'return_date' => 'required|date|after:start_date',
            'status' => 'required|string|max:255',
            'user_id' => 'required|integer|exists:users,id',
        ];
    }
}
