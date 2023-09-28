<?php

namespace App\Http\Requests;

use App\Models\File;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UploadFileRequest extends FormRequest
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
            'files.*' => [
                'required',
                'file',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $file = File::where("name", $value->getClientOriginalName())
                            ->where('user_id', Auth::id())
                            ->whereNull('deleted_at')
                            ->where('folder_id', $this->parent_folder ?? null)
                            ->exists();

                        if ($file) {
                            $fail('File already exists');
                        }
                    }
                }
            ]
        ];
    }
}
