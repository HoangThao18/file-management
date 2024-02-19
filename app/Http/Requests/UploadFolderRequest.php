<?php

namespace App\Http\Requests;

use App\Http\Libraries\HttpResponse;
use App\Models\Folder;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UploadFolderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $paths = array_filter($this->relative_paths, function ($item) {
            return $item !== null;
        });
        $this->merge([
            'file_paths' => $paths,
            'folder_name' => $this->detectFolderName($paths)
        ]);
    }

    protected function passedValidation()
    {
        $data = $this->validated();
        $this->replace([
            'files_tree' => $this->buildFileTree($this->file_paths, $data['files'])
        ]);
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
            'files' => ["required", "Array"],
            'folder_name' => [
                'string',
                Rule::unique('folders', 'name')->where('user_id', Auth::id())->where('parent_folder', $this->input('parent_id') ?? null)
            ]
        ];
    }

    public function detectFolderName($paths)
    {
        if (!$paths) {
            return null;
        }
        $parts = explode("/", $paths[0]);
        return $parts[0];
    }

    private function buildFileTree($paths, $files)
    {
        $paths = array_slice($paths, 0, count($files));

        $tree = [];

        foreach ($paths as $ind => $value) {
            $parts = explode("/", $value);

            $currentNode = &$tree;

            foreach ($parts as $i => $part) {

                if (!isset($currentNode[$part])) {
                    $currentNode[$part] = [];
                }

                if ($i === count($parts) - 1) {
                    $currentNode[$part] = $files[$ind];
                } else {
                    $currentNode = &$currentNode[$part];
                }
            }
        }


        return $tree;
    }
}
