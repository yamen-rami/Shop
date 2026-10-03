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
            "name" => 'required|string|min:3',
            "desc" => 'required|string|min:3',
            "catagory_id" => 'nullable|exists:catagories,id',
            "tags" => 'nullable|array',
            "tags.*" => 'exists:tags,id',
            "int_price" => 'required|numeric',
            "price" => 'required|numeric',
            "featured" => 'nullable',
            "quantity" => 'required|integer|min:1',
            "image" => 'nullable|image',
        ];
    }
}
