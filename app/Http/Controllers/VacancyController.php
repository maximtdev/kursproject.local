<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class VacancyController extends Controller
{
    /**
     * Просмотр всех опубликованных вакансий
     */
    public function index(): JsonResponse
    {
        $vacancies = Vacancy::published()
            ->with('employer:id,name,surname,email')
            ->select('id', 'title', 'company', 'location', 'salary', 'type', 'description', 'requirements', 'employer_id')
            ->get();

        return response()->json($vacancies);
    }

    /**
     * Просмотр конкретной вакансии
     */
    public function show(Vacancy $vacancy): JsonResponse
    {
        // Проверка: только опубликованные вакансии или вакансии текущего работодателя
        if ($vacancy->status !== 'published' && $vacancy->employer_id !== Auth::id()) {
            return response()->json(['message' => 'Вакансия не найдена'], 404);
        }

        $vacancy->load('employer:id,name,surname,email');

        return response()->json([
            'id' => $vacancy->id,
            'title' => $vacancy->title,
            'company' => $vacancy->company,
            'location' => $vacancy->location,
            'salary' => $vacancy->salary,
            'type' => $vacancy->type,
            'description' => $vacancy->description,
            'requirements' => $vacancy->requirements,
            'employerId' => $vacancy->employer_id,
        ]);
    }
}
