<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vacancy;
use App\Models\User;

class VacancySeeder extends Seeder
{
    public function run(): void
    {
        // Получаем работодателей
        $employer1 = User::where('email', 'smirnov@techcorp.ru')->first();
        $employer2 = User::where('email', 'kozlova@designstudio.ru')->first();

        // Вакансии от первого работодателя (Tech Corp)
        Vacancy::create([
            'title' => 'Senior Frontend Developer',
            'company' => 'Tech Corp',
            'location' => 'Москва',
            'salary' => 'от 200 000 до 300 000 ₽',
            'type' => 'Полная занятость',
            'description' => 'Ищем опытного Frontend разработчика для работы над крупными проектами. Работа в команде профессионалов, интересные задачи и возможность профессионального роста.',
            'requirements' => json_encode([
                'Опыт работы с React от 3 лет',
                'Знание TypeScript',
                'Опыт работы с state management (Redux, MobX)',
                'Понимание принципов responsive design'
            ]),
            'status' => 'published',
            'employer_id' => $employer1->id,
        ]);

        Vacancy::create([
            'title' => 'Backend Developer (Node.js)',
            'company' => 'Tech Corp',
            'location' => 'Санкт-Петербург',
            'salary' => 'от 180 000 до 280 000 ₽',
            'type' => 'Полная занятость',
            'description' => 'Разработка и поддержка backend части продукта. Работа с микросервисной архитектурой.',
            'requirements' => json_encode([
                'Опыт работы с Node.js',
                'Знание баз данных (PostgreSQL, MongoDB)',
                'Опыт работы с Docker',
                'Знание REST API'
            ]),
            'status' => 'published',
            'employer_id' => $employer1->id,
        ]);

        Vacancy::create([
            'title' => 'Junior QA Engineer',
            'company' => 'Tech Corp',
            'location' => 'Удаленно',
            'salary' => 'от 80 000 до 120 000 ₽',
            'type' => 'Частичная занятость',
            'description' => 'Поиск и документирование багов, написание тестовой документации, участие в ручном тестировании.',
            'requirements' => json_encode([
                'Базовые знания тестирования ПО',
                'Внимательность к деталям',
                'Умение составлять баг-репорты',
                'Опыт работы с Jira'
            ]),
            'status' => 'published',
            'employer_id' => $employer1->id,
        ]);

        // Вакансии от второго работодателя (Design Studio)
        Vacancy::create([
            'title' => 'UX/UI Designer',
            'company' => 'Design Studio',
            'location' => 'Удаленно',
            'salary' => 'от 120 000 до 180 000 ₽',
            'type' => 'Удаленная работа',
            'description' => 'Проектирование пользовательских интерфейсов для веб и мобильных приложений.',
            'requirements' => json_encode([
                'Опыт работы от 2 лет',
                'Владение Figma',
                'Портфолио с реализованными проектами',
                'Понимание принципов UX'
            ]),
            'status' => 'published',
            'employer_id' => $employer2->id,
        ]);

        Vacancy::create([
            'title' => 'Product Manager',
            'company' => 'Design Studio',
            'location' => 'Москва',
            'salary' => 'от 180 000 до 280 000 ₽',
            'type' => 'Полная занятость',
            'description' => 'Управление продуктом от идеи до релиза. Работа с командой разработки и заказчиками.',
            'requirements' => json_encode([
                'Опыт работы Product Manager от 3 лет',
                'Знание Agile/Scrum',
                'Опыт работы с аналитикой',
                'Навыки презентации и коммуникации'
            ]),
            'status' => 'published',
            'employer_id' => $employer2->id,
        ]);

        // Черновик вакансии
        Vacancy::create([
            'title' => 'Middle Python Developer',
            'company' => 'Tech Corp',
            'location' => 'Новосибирск',
            'salary' => 'от 150 000 до 220 000 ₽',
            'type' => 'Гибридная работа',
            'description' => 'Разработка и поддержка внутренних сервисов компании.',
            'requirements' => json_encode([
                'Опыт работы с Python от 2 лет',
                'Знание Django/Flask',
                'Опыт работы с базами данных'
            ]),
            'status' => 'draft',
            'employer_id' => $employer1->id,
        ]);
    }
}
