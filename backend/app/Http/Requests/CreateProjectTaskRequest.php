<?php

namespace App\Http\Requests;

use App\Dto\CreateProjectTaskDto;
use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateProjectTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'status' => ['required', new Enum(TaskStatus::class)],
            'completion_date' => ['nullable', 'date'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file'],
        ];
    }

    public function toData(): CreateProjectTaskDto
    {
        return CreateProjectTaskDto::from($this->validated());
    }
}
