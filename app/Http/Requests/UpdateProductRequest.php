<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;

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
            "int_price" => 'required|numeric|min:0|lt:price',
            "price" => 'required|numeric|gt:0',
            "featured" => 'required|boolean',
            "quantity" => 'required|integer|min:0',
            'image' => ['nullable', 'image', 'max:5120'],
            'color_id' => ['required_with:image', 'nullable', 'integer', 'exists:colors,id'],
            'existing_images' => ['sometimes', 'array', 'max:12'],
            'existing_images.*' => ['array:file,color_id,remove'],
            'existing_images.*.file' => ['nullable', 'image', 'max:5120'],
            'existing_images.*.color_id' => ['required', 'integer', 'exists:colors,id'],
            'existing_images.*.remove' => ['sometimes', 'boolean'],
            'image_count' => ['required_with:images', 'integer', 'min:0', 'max:12'],
            'images' => [Rule::requiredIf(fn () => $this->integer('image_count') > 0), 'nullable', 'array', 'max:12', 'size:'.max(0, min(12, $this->integer('image_count')))],
            'images.*.file' => ['required', 'image', 'max:5120'],
            'images.*.color_id' => ['required', 'integer', 'exists:colors,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['featured' => $this->boolean('featured'), 'tags' => $this->input('tags', [])]);
    }

    public function after(): array
    {
        return [function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $currentIds = $this->route('product')->images()->pluck('id');
            $changes = $this->input('existing_images', []);
            foreach (array_keys($changes) as $id) {
                if (!$currentIds->contains($id)) {
                    $validator->errors()->add('existing_images', 'Only images belonging to this product can be updated.');
                    return;
                }
            }

            $removed = collect($changes)->filter(fn ($image) => (bool) ($image['remove'] ?? false))->count();
            $total = $currentIds->count() - $removed + count($this->file('images', []));
            if ($total > 12) {
                $validator->errors()->add('images', 'A product can have up to 12 images. Remove a photo before adding more.');
            }
            if ($currentIds->isNotEmpty() && $total === 0) {
                $validator->errors()->add('existing_images', 'Keep at least one product image or upload a replacement.');
            }
        }];
    }
}
