<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            //
            "name" => 'required|string|min:3' , 
            "desc" => 'required|string|min:3' , 
            "int_price" => 'required|integer|min:1' , 
            "price" => 'required|integer|min:1' , 
            "quantity" => 'required|integer|min:1' , 
            "image" => 'nullable|image', 
        ];
    }
}
