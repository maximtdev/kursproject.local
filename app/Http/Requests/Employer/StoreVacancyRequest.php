<?php

namespace App\Http\Requests\Employer;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreVacancyRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Проверка: пользователь должен быть работодателем
        return auth()->check() && auth()->user()->role === 'employer';
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],
            'company' => [
                'required',
                'string',
                'min:4',
                'max:255',
            ],
            'location' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],
            'salary' => [
                'required',
                'string',
                'min:5',
                'max:50',
            ],
            'type' => [
                'required',
                'string',
                'in:Полная занятость,Частичная занятость,Удаленная работа,Стажировка',
            ],
            'description' => [
                'required',
                'string',
                'min:50',
                'max:5000',
            ],
            'requirements' => [
                'required',
                'array',
                'min:1',
            ],
            'requirements.*' => [
                'required',
                'string',
                'min:10',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Поле "Название вакансии" обязательно для заполнения.',
            'title.min' => 'Название вакансии должно содержать минимум 2 символа.',
            'title.max' => 'Название вакансии не должно превышать 255 символов.',
            'company.required' => 'Поле "Компания" обязательно для заполнения.',
            'company.min' => 'Название компании должно содержать минимум 4 символа.',
            'location.required' => 'Поле "Местоположение" обязательно для заполнения.',
            'salary.required' => 'Поле "Зарплата" обязательно для заполнения.',
            'type.required' => 'Поле "Тип занятости" обязательно для заполнения.',
            'type.in' => 'Выбран некорректный тип занятости.',
            'description.required' => 'Поле "Описание" обязательно для заполнения.',
            'description.min' => 'Описание должно содержать минимум 50 символов.',
            'description.max' => 'Описание не должно превышать 5000 символов.',
            'requirements.required' => 'Поле "Требования" обязательно для заполнения.',
            'requirements.min' => 'Необходимо указать минимум одно требование.',
            'requirements.*.required' => 'Каждое требование обязательно для заполнения.',
            'requirements.*.min' => 'Требование должно содержать минимум 10 символов.',
            'requirements.*.max' => 'Требование не должно превышать 1000 символов.',
        ];
    }
}
