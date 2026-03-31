<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Favorite;
use App\Models\User;
use App\Models\Vacancy;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        // Получаем соискателей и вакансии
        $applicant1 = User::where('email', 'titov@mail.ru')->first();
        $applicant3 = User::where('email', 'd.ivanov@inbox.ru')->first();
        $vacancy1 = Vacancy::where('title', 'Senior Frontend Developer')->first();
        $vacancy2 = Vacancy::where('title', 'UX/UI Designer')->first();
        $vacancy4 = Vacancy::where('title', 'Product Manager')->first();

        // Избранное для первого соискателя
        Favorite::create([
            'user_id' => $applicant1->id,
            'vacancy_id' => $vacancy1->id,
        ]);

        Favorite::create([
            'user_id' => $applicant1->id,
            'vacancy_id' => $vacancy2->id,
        ]);

        // Избранное для третьего соискателя
        Favorite::create([
            'user_id' => $applicant3->id,
            'vacancy_id' => $vacancy4->id,
        ]);

        Favorite::create([
            'user_id' => $applicant3->id,
            'vacancy_id' => $vacancy1->id,
        ]);
    }
}
