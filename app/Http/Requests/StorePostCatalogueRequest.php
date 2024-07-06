<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostCatalogueRequest extends FormRequest
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
            'name' => 'required',
            'canonical' => 'required|unique:post_catalogue_language'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => "Bạn chưa nhập tên tiêu đề.",
            'canonical.required' => "Bạn chưa nhập đường dẫn",
            'canonical.unique' => "Đường dẫn đã tồn tại"
        ];
    }
}
