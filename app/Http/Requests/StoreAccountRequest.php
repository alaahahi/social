<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountRequest extends FormRequest
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
            'user_name' => 'required|string|max:255',
            'platform' => 'required',
            'notes' => 'nullable',
            'created'=>'nullable',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional avatar upload validation
        ];
    }

    public function messages()
    {
        return [
            'user_name.required' => __('rules.User_Name_is_required'),
            'name.required' => __('rules.Platform_is_required'),
            'avatar.image' => __('rules.The_avatar_must_be_an_image_file'),
            'avatar.mimes' => __('rules.The_avatar_must_be_a_file_of_type_jpeg_png_jpg_gif'),
            'avatar.max' => __('rules.The_avatar_must_not_exceed_2MB'),
        ];
        
    }

    
}
