<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DownloadRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // 'parent_id' => ['nullable', Rule::exists('folders', 'id')->whereNull('deleted_at')],
            "fileIds" => ['Nullable', 'Array'],
            "fileIds.*" => Rule::exists('files', 'id')->whereNull('deleted_at'),
            "folderIds" => ['Nullable', 'Array'],
            "folderIds.*" => Rule::exists('folders', 'id')->whereNull('deleted_at')
        ];
    }

    public function messages()
    {
        return [
            "fileIds.*" => "the selected file invalid",
            "folderIds.*" => "the selected folder invalid",
        ];
    }
}
