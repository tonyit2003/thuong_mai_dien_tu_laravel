<?php

namespace App\Http\Requests;

use App\Rules\CheckMenuItem;
use Illuminate\Foundation\Http\FormRequest;

class StoreMenuRequest extends FormRequest
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
            'menu_catalogue_id' => 'gt:0',
            // kiểm tra thuộc tính name của mảng menu trong dữ liệu đầu vào là bắt buộc (required).
            'menu.name' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'menu_catalogue_id.gt' => "Bạn chưa chọn vị trí hiển thị.",
            'menu.name.required' => "Bạn phải tạo ít nhất một menu.",
        ];
    }
}
