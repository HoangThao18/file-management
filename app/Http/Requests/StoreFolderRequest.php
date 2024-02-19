<?php

namespace App\Http\Requests;

use App\Http\Libraries\HttpResponse;
use App\Models\Folder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreFolderRequest extends FormRequest
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
            'parent_id' => ['nullable', Rule::exists('folders', 'id')->whereNull('deleted_at')],
            'name' => [
                'required',
                Rule::unique('folders', 'name')->where('user_id', Auth::id())->where('parent_folder', $this->input('parent_id') ?? null)->whereNull('deleted_at')
            ],
        ];
    }
}
