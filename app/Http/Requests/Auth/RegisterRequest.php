<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Определение прав доступа к запросу.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[а-яА-ЯёЁa-zA-Z\s]+$/u',
            ],
            'surname' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[а-яА-ЯёЁa-zA-Z\s]+$/u',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'max:32',
                'regex:/^(?=.*[A-Z])(?=.*\d.*\d)(?=.*[\W_]).+$/',
            ],
            'role' => [
                'sometimes',
                'in:applicant,employer',
            ],
        ];
    }

    /**
     * Сообщения об ошибках валидации.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Поле "Имя" обязательно для заполнения.',
            'name.min' => 'Имя должно содержать минимум 2 символа.',
            'name.max' => 'Имя не должно превышать 50 символов.',
            'name.regex' => 'Имя может содержать только буквы и пробелы.',
            'surname.regex' => 'Фамилия может содержать только буквы и пробелы.',
            'email.required' => 'Поле "Почта" обязательно для заполнения.',
            'email.email' => 'Некорректный формат email.',
            'email.unique' => 'Пользователь с таким email уже существует.',
            'password.required' => 'Поле "Пароль" обязательно для заполнения.',
            'password.min' => 'Пароль должен содержать минимум 6 символов.',
            'password.max' => 'Пароль не должен превышать 32 символа.',
            'password.regex' => 'Пароль должен содержать минимум 1 заглавную букву, 2 цифры и 1 спецсимвол.',
        ];
    }
}
