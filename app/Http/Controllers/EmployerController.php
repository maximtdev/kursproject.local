<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Employer\StoreVacancyRequest;
use App\Http\Requests\Employer\UpdateVacancyRequest;
use App\Models\Response;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class EmployerController extends Controller
{
    /**
     * Просмотр всех вакансий текущего работодателя
     */
    public function index(): JsonResponse
    {
        $vacancies = Vacancy::where('employer_id', Auth::id())
            ->select('id', 'title', 'company', 'location', 'salary', 'type', 'description', 'requirements', 'status', 'employer_id')
            ->get();

        return response()->json($vacancies);
    }

    /**
     * Создание новой вакансии
     */
    public function store(StoreVacancyRequest $request): JsonResponse
    {
        $vacancy = Vacancy::create([
            'title' => $request->title,
            'company' => $request->company,
            'location' => $request->location,
            'salary' => $request->salary,
            'type' => $request->type,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'status' => 'published',
            'employer_id' => Auth::id(),
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
            'created_at' => $vacancy->created_at,
            'status' => $vacancy->status,
        ], 201);
    }

    /**
     * Просмотр конкретной вакансии работодателя
     */
    public function show(Vacancy $vacancy): JsonResponse
    {
        // Проверка прав доступа: только владелец вакансии
        if ($vacancy->employer_id !== Auth::id()) {
            return response()->json(['message' => 'Доступ запрещён'], 403);
        }

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

    /**
     * Редактирование вакансии
     */
    public function update(UpdateVacancyRequest $request, Vacancy $vacancy): JsonResponse
    {
        // Проверка прав доступа: только владелец вакансии
        if ($vacancy->employer_id !== Auth::id()) {
            return response()->json(['message' => 'Доступ запрещён'], 403);
        }

        $vacancy->update([
            'title' => $request->title,
            'company' => $request->company,
            'location' => $request->location,
            'salary' => $request->salary,
            'type' => $request->type,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'status' => $request->status ?? $vacancy->status,
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
            'created_at' => $vacancy->created_at,
            'updated_at' => $vacancy->updated_at,
            'status' => $vacancy->status,
        ]);
    }

    /**
     * Удаление вакансии
     */
    public function destroy(Vacancy $vacancy): JsonResponse
    {
        // Проверка прав доступа: только владелец вакансии
        if ($vacancy->employer_id !== Auth::id()) {
            return response()->json(['message' => 'Доступ запрещён'], 403);
        }

        $vacancy->delete();

        return response()->json(['message' => 'Вакансия успешно удалена'], 200);
    }

    /**
     * Просмотр откликов на вакансии работодателя
     */
    public function responses(): JsonResponse
    {
        $responses = Response::whereHas('vacancy', function ($query) {
            $query->where('employer_id', Auth::id());
        })
            ->with('vacancy:id,title,company')
            ->select('id', 'vacancy_id', 'name', 'surname', 'email', 'phone', 'about', 'social_url', 'resume_url', 'status', 'applied_at')
            ->get()
            ->map(function ($response) {
                return [
                    'id' => $response->id,
                    'vacancy_id' => $response->vacancy_id,
                    'vacancy' => [
                        'id' => $response->vacancy->id,
                        'title' => $response->vacancy->title,
                        'company' => $response->vacancy->company,
                    ],
                    'name' => $response->name,
                    'surname' => $response->surname,
                    'email' => $response->email,
                    'phone' => $response->phone,
                    'about' => $response->about,
                    'social_url' => $response->social_url,
                    'resume_url' => $response->resume_url,
                    'status' => $response->status,
                    'applied_at' => $response->applied_at,
                ];
            });

        return response()->json($responses);
    }

    /**
     * Изменение статуса отклика
     */
    public function updateResponseStatus(Response $response, \Illuminate\Http\Request $request): JsonResponse
    {
        // Проверка прав доступа: только работодатель вакансии
        if ($response->vacancy->employer_id !== Auth::id()) {
            return response()->json(['message' => 'Доступ запрещён'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:submitted,viewed,invited,rejected',
        ]);

        $response->update(['status' => $validated['status']]);

        return response()->json([
            'id' => $response->id,
            'status' => $response->status,
            'updated_at' => $response->updated_at,
        ]);
    }
}
