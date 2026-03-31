<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Response;
use App\Models\User;
use App\Models\Vacancy;

class ResponseSeeder extends Seeder
{
    public function run(): void
    {
        // Получаем соискателей и вакансии
        $applicant1 = User::where('email', 'titov@mail.ru')->first();
        $applicant2 = User::where('email', 'petrova.anna@gmail.com')->first();
        $vacancy1 = Vacancy::where('title', 'Senior Frontend Developer')->first();
        $vacancy2 = Vacancy::where('title', 'UX/UI Designer')->first();
        $vacancy3 = Vacancy::where('title', 'Backend Developer (Node.js)')->first();

        // Отклики от первого соискателя
        Response::create([
            'vacancy_id' => $vacancy1->id,
            'user_id' => $applicant1->id,
            'name' => 'Максим',
            'surname' => 'Титов',
            'email' => 'titov@mail.ru',
            'phone' => '+79995554444',
            'about' => 'Максим, 18 лет, студент ТЭПК 3 курс IT. Опыт работы с фронтенд-технологиями, базовые знания HTML/CSS/JavaScript.',
            'social_url' => 'https://linkedin.com/in/maxim-titov',
            'resume_url' => 'https://cloud.example.com/resumes/titov_resume.pdf',
            'status' => 'submitted',
            'applied_at' => now()->subDays(3),
        ]);

        Response::create([
            'vacancy_id' => $vacancy2->id,
            'user_id' => $applicant1->id,
            'name' => 'Максим',
            'surname' => 'Титов',
            'email' => 'titov@mail.ru',
            'phone' => '+79995554444',
            'about' => 'Максим, 18 лет, студент ТЭПК 3 курс IT. Интересуюсь дизайном интерфейсов, изучаю основы проектирования.',
            'social_url' => 'https://linkedin.com/in/maxim-titov',
            'resume_url' => 'https://cloud.example.com/resumes/titov_resume.pdf',
            'status' => 'viewed',
            'applied_at' => now()->subDays(5),
        ]);

        // Отклики от второго соискателя
        Response::create([
            'vacancy_id' => $vacancy1->id,
            'user_id' => $applicant2->id,
            'name' => 'Анна',
            'surname' => 'Петрова',
            'email' => 'petrova.anna@gmail.com',
            'phone' => '+79123456789',
            'about' => 'Frontend-разработчик с 4-летним опытом. Специализируюсь на React и современных инструментах сборки. Участвовала в создании крупных веб-приложений для финтех сектора.',
            'social_url' => 'https://linkedin.com/in/anna-petrova',
            'resume_url' => 'https://cloud.example.com/resumes/petrova_resume.pdf',
            'status' => 'invited',
            'applied_at' => now()->subDays(2),
        ]);

        Response::create([
            'vacancy_id' => $vacancy3->id,
            'user_id' => $applicant2->id,
            'name' => 'Анна',
            'surname' => 'Петрова',
            'email' => 'petrova.anna@gmail.com',
            'phone' => '+79123456789',
            'about' => 'Frontend-разработчик с 4-летним опытом. Есть опыт работы с бэкендом на Node.js, понимаю принципы построения API.',
            'social_url' => 'https://linkedin.com/in/anna-petrova',
            'resume_url' => 'https://cloud.example.com/resumes/petrova_resume.pdf',
            'status' => 'rejected',
            'applied_at' => now()->subDays(7),
        ]);
    }
}
