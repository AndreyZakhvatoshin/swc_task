<?php

namespace App\Http\Requests;

use App\Dto\CreateProjectTaskDto;
use App\Dto\UpdateTaskDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'description' => ['required', 'string'],
            'completion_date' => ['nullable', 'date'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file'],
            'attachments_url' => ['nullable', 'array'],
            'attachments_url.*' => ['url']

        ];
    }

    public function toData(): UpdateTaskDto
    {
        return UpdateTaskDto::from($this->validated());
    }
}
