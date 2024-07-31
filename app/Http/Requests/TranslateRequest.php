<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class TranslateRequest extends FormRequest
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
            'translate_name' => 'required',
            'translate_canonical' => [
                'required',
                /*
                    $attribute: translate_canonical
                    $value: giá trị translate_canonical
                    $fail: cung cấp một thông báo lỗi tùy chỉnh.
                */
                function ($attribute, $value, $fail) {
                    $option = $this->input('option');
                    $exist = DB::table('routers')
                        ->where('canonical', $value)
                        ->where(function ($query) use ($option) {
                            $query->where('language_id', '<>', $option['languageId'])->orWhere('module_id', '<>', $option['id']);
                        })->exists();
                    // Nếu tồn tại bản ghi thỏa mãn điều kiện, gọi callback $fail với thông báo lỗi
                    if ($exist) {
                        $fail('Đường dẫn đã tồn tại.');
                    }
                }
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'translate_name.required' => "Bạn chưa nhập tên tiêu đề.",
            'translate_canonical.required' => "Bạn chưa nhập đường dẫn.",
            'translate_canonical.unique' => "Đường dẫn đã tồn tại.",
        ];
    }
}
