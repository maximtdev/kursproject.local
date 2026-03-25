<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,      // Сначала пользователи (работодатели и соискатели)
            VacancySeeder::class,   // Затем вакансии (ссылаются на работодателей)
            ResponseSeeder::class,  // Отклики (ссылаются на пользователей и вакансии)
            FavoriteSeeder::class,  // Избранное (ссылаются на пользователей и вакансии)
        ]);
    }
}
