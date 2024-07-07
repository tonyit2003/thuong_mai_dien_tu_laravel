<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostCatalogueRequest extends FormRequest
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
            'canonical' => 'required|unique:post_catalogue_language,canonical,' . $this->id . ',post_catalogue_id' // giá trị canonical phải là duy nhất trong bảng post_catalogue_language và bỏ qua kiểm tra duy nhất cho bản ghi hiện tại được xác định bởi $this->id thông qua post_catalogue_id trong bảng post_catalogue_language
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
