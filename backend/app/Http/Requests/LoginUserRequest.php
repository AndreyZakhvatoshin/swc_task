<?php

namespace App\Http\Requests;

use App\Dto\LoginUserDto;
use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function toData(): LoginUserDto
    {
        return LoginUserDto::from($this->validated());
    }
}
