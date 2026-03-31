<?php

namespace App\Http\Requests\Response;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateResponseRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Проверка: пользователь должен быть владельцем отклика
        $response = $this->route('response');
        return auth()->check() && auth()->id() === $response->user_id;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'required_with_all:name,surname,email,phone,about',
                'string',
                'min:2',
                'max:50',
                'regex:/^[а-яА-ЯёЁa-zA-Z\s]+$/u',
            ],
            'surname' => [
                'sometimes',
                'required_with_all:name,surname,email,phone,about',
                'string',
                'min:2',
                'max:50',
                'regex:/^[а-яА-ЯёЁa-zA-Z\s]+$/u',
            ],
            'email' => [
                'sometimes',
                'required_with_all:name,surname,email,phone,about',
                'email',
                'max:255',
            ],
            'phone' => [
                'sometimes',
                'required_with_all:name,surname,email,phone,about',
                'string',
                'regex:/^\+7\d{10}$/',
            ],
            'about' => [
                'sometimes',
                'required_with_all:name,surname,email,phone,about',
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
                'max:10240',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required_with_all' => 'При обновлении данных необходимо заполнить все поля: имя, фамилия, email, телефон, описание.',
            'name.regex' => 'Имя может содержать только буквы и пробелы.',
            'surname.regex' => 'Фамилия может содержать только буквы и пробелы.',
            'phone.regex' => 'Номер телефона должен быть в формате +7XXXXXXXXXX.',
            'about.min' => 'Описание должно содержать минимум 10 символов.',
            'about.max' => 'Описание не должно превышать 2000 символов.',
            'resume_file.mimes' => 'Резюме должно быть в формате PDF, DOC или DOCX.',
            'resume_file.max' => 'Размер файла резюме не должен превышать 10 МБ.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $resumeUrl = $this->input('resume_url');
            $resumeFile = $this->file('resume_file');

            // Если переданы новые данные, проверяем наличие хотя бы одного поля резюме
            if ($this->hasAny(['name', 'surname', 'email', 'phone', 'about']) && empty($resumeUrl) && empty($resumeFile)) {
                $validator->errors()->add('resume', 'Необходимо указать либо ссылку на резюме, либо загрузить файл.');
            }
        });
    }
}
