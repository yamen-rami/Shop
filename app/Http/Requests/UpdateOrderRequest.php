<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Middleware\TrustProxies;

class UpdateOrderRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ['required' , "string"],
            "price" => ['nullable', 'numeric', 'min:0'],
            "quantity" => ['required', 'integer', 'min:1'],
            "location" => ['required' , "string"],
            "product_id" => ['required' , "exists:products,id"]
            // "quantity" => 'required|integer',
            // "location" => 'required|string|min:3',
        ];
    }
}
