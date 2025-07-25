<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
        return [
            'name'=> 'required|string',
            'description'=> 'required|string',
            'pictures.*' => 'nullable|image|mimes:jpeg,png|max:15048',
            'phone' => 'nullable|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
            'tg' => 'nullable|string'
        ];
    }
}
