<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DeleteFilePermanentlyRequest extends FormRequest
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
            "fileIds.*" => [Rule::exists('files', 'id')->where('user_id', Auth::id())->whereNotNull('deleted_at'),],
            "folderIds.*" => [Rule::exists('folders', 'id')->where('user_id', Auth::id())->whereNotNull("deleted_at")],
        ];
    }
}
