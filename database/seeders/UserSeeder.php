<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Работодатели
        User::create([
            'name' => 'Алексей',
            'surname' => 'Смирнов',
            'email' => 'smirnov@techcorp.ru',
            'password' => bcrypt('employer123'),
            'role' => 'employer',
        ]);

        User::create([
            'name' => 'Мария',
            'surname' => 'Козлова',
            'email' => 'kozlova@designstudio.ru',
            'password' => bcrypt('employer456'),
            'role' => 'employer',
        ]);

        // Соискатели
        User::create([
            'name' => 'Максим',
            'surname' => 'Титов',
            'email' => 'titov@mail.ru',
            'password' => bcrypt('applicant123'),
            'role' => 'applicant',
        ]);

        User::create([
            'name' => 'Анна',
            'surname' => 'Петрова',
            'email' => 'petrova.anna@gmail.com',
            'password' => bcrypt('applicant456'),
            'role' => 'applicant',
        ]);

        User::create([
            'name' => 'Дмитрий',
            'surname' => 'Иванов',
            'email' => 'd.ivanov@inbox.ru',
            'password' => bcrypt('applicant789'),
            'role' => 'applicant',
        ]);

        User::create([
            'name' => 'Екатерина',
            'surname' => 'Соколова',
            'email' => 'sokolova.katya@mail.ru',
            'password' => bcrypt('applicant321'),
            'role' => 'applicant',
        ]);
    }
}
