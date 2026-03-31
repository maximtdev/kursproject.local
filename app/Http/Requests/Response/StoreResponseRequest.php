<?php

namespace App\Http\Requests\Response;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreResponseRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Проверка: пользователь должен быть соискателем
        return auth()->check() && auth()->user()->role === 'applicant';
    }

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
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[а-яА-ЯёЁa-zA-Z\s]+$/u',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'phone' => [
                'required',
                'string',
                'regex:/^\+7\d{10}$/',
            ],
            'about' => [
                'required',
                'string',
                'min:10',
                'max:2000',
            ],
            'social_url' => [
                'nullable',
                'url',
                'max:500',
            ],
            'resume_url' => [
                'nullable',
                'url',
                'max:500',
            ],
            'resume_file' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx',
                'max:10240', // 10MB
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле "Имя" обязательно для заполнения.',
            'name.regex' => 'Имя может содержать только буквы и пробелы.',
            'surname.required' => 'Поле "Фамилия" обязательно для заполнения.',
            'surname.regex' => 'Фамилия может содержать только буквы и пробелы.',
            'email.required' => 'Поле "Email" обязательно для заполнения.',
            'email.email' => 'Некорректный формат email.',
            'phone.required' => 'Поле "Номер телефона" обязательно для заполнения.',
            'phone.regex' => 'Номер телефона должен быть в формате +7XXXXXXXXXX.',
            'about.required' => 'Поле "О себе" обязательно для заполнения.',
            'about.min' => 'Описание должно содержать минимум 10 символов.',
            'about.max' => 'Описание не должно превышать 2000 символов.',
            'social_url.url' => 'Некорректный формат URL соцсети.',
            'resume_url.url' => 'Некорректный формат ссылки на резюме.',
            'resume_file.mimes' => 'Резюме должно быть в формате PDF, DOC или DOCX.',
            'resume_file.max' => 'Размер файла резюме не должен превышать 10 МБ.',
        ];
    }

    /**
     * Валидация взаимоисключения полей.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $resumeUrl = $this->input('resume_url');
            $resumeFile = $this->file('resume_file');

            if (empty($resumeUrl) && empty($resumeFile)) {
                $validator->errors()->add('resume', 'Необходимо указать либо ссылку на резюме, либо загрузить файл.');
            }
        });
    }
}
