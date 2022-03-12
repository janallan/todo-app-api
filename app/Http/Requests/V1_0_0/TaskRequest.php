<?php

namespace App\Http\Requests\V1_0_0;

use App\Enums\TaskPrioritization;
use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get custom attributes for validation errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'title' => 'Title',
            'description' => 'Description',
            'due_date' => 'Due Date',
            'prioritization' => 'Prioritization Level',
            'tags' => 'Tags',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:500'],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
            'prioritization' => ['required', 'in:' . implode(",", TaskPrioritization::getValues())],
            'tags' => ['sometimes', 'array',
                    function($attribute, $value, $fail) {
                        if (!$this->checkTags()) {
                            return $fail(__('validation.tag.invalid'));
                        }
                    }
                ]
        ];
    }

    /**
     * Check all tags if exist in user
     *
     * @return bool
     * @creator Jan Allan Verano
     */
    private function checkTags(): bool
    {
        $user = request()->user();

        foreach ($this->tags as $tag) {
            if (!$user->tags->where('name', $tag)->first()) {
                return false;
            }
        }

        return true;
    }
}
