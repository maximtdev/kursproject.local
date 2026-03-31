<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class FavoriteController extends Controller
{
    /**
     * Просмотр всех избранных вакансий пользователя
     */
    public function index(): JsonResponse
    {
        $favorites = Favorite::where('user_id', Auth::id())
            ->with('vacancy:id,title,company,location,salary,type,description,requirements,employer_id')
            ->get()
            ->map(function ($favorite) {
                return [
                    'id' => $favorite->vacancy->id,
                    'title' => $favorite->vacancy->title,
                    'company' => $favorite->vacancy->company,
                    'location' => $favorite->vacancy->location,
                    'salary' => $favorite->vacancy->salary,
                    'type' => $favorite->vacancy->type,
                    'description' => $favorite->vacancy->description,
                    'requirements' => $favorite->vacancy->requirements,
                    'employerId' => $favorite->vacancy->employer_id,
                ];
            });

        return response()->json($favorites);
    }

    /**
     * Добавление вакансии в избранное
     */
    public function store(Vacancy $vacancy): JsonResponse
    {
        // Проверка: вакансия должна быть опубликована
        if ($vacancy->status !== 'published') {
            return response()->json(['message' => 'Вакансия недоступна'], 403);
        }

        // Проверка на дублирование
        $existing = Favorite::where('user_id', Auth::id())
            ->where('vacancy_id', $vacancy->id)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Вакансия уже в избранном'], 409);
        }

        Favorite::create([
            'user_id' => Auth::id(),
            'vacancy_id' => $vacancy->id,
        ]);

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
        ], 201);
    }

    /**
     * Удаление вакансии из избранного
     */
    public function destroy(Vacancy $vacancy): JsonResponse
    {
        $favorite = Favorite::where('user_id', Auth::id())
            ->where('vacancy_id', $vacancy->id)
            ->first();

        if (!$favorite) {
            return response()->json(['message' => 'Вакансия не найдена в избранном'], 404);
        }

        $favorite->delete();

        return response()->json(['message' => 'Вакансия удалена из избранного'], 200);
    }
}
