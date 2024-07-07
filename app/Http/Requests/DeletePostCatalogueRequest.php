<?php

namespace App\Http\Requests;

use App\Models\PostCatalogue;
use App\Rules\CheckPostCatalogueChildrenRule;
use Illuminate\Foundation\Http\FormRequest;

class DeletePostCatalogueRequest extends FormRequest
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
        $id = $this->route('id'); // Lấy giá trị của tham số id từ URL hiện tại.
        return [
            'name' => [
                // quy tắc xác thực tự định nghĩa
                new CheckPostCatalogueChildrenRule($id)
            ]
        ];
    }
}
