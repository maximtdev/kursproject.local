<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Response\StoreResponseRequest;
use App\Http\Requests\Response\UpdateResponseRequest;
use App\Models\Response;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ResponseController extends Controller
{
    /**
     * Просмотр всех откликов текущего пользователя
     */
    public function index(): JsonResponse
    {
        $responses = Response::where('user_id', Auth::id())
            ->with('vacancy:id,title,company,location,salary,type')
            ->select('id', 'vacancy_id', 'status', 'applied_at', 'name', 'surname', 'email', 'phone', 'about', 'social_url', 'resume_url')
            ->get()
            ->map(function ($response) {
                return [
                    'id' => $response->id,
                    'vacancy_id' => $response->vacancy_id,
                    'status' => $response->status,
                    'applied_at' => $response->applied_at,
                    'name' => $response->name,
                    'surname' => $response->surname,
                    'email' => $response->email,
                    'phone' => $response->phone,
                    'about' => $response->about,
                    'social_url' => $response->social_url,
                    'resume_url' => $response->resume_url,
                    'vacancy' => [
                        'id' => $response->vacancy->id,
                        'title' => $response->vacancy->title,
                        'company' => $response->vacancy->company,
                        'location' => $response->vacancy->location,
                        'salary' => $response->vacancy->salary,
                        'type' => $response->vacancy->type,
                    ]
                ];
            });

        return response()->json($responses);
    }

    /**
     * Просмотр конкретного отклика
     */
    public function show(Response $response): JsonResponse
    {
        // Проверка прав доступа: только владелец отклика или работодатель вакансии
        if ($response->user_id !== Auth::id() && $response->vacancy->employer_id !== Auth::id()) {
            return response()->json(['message' => 'Доступ запрещён'], 403);
        }

        $response->load('vacancy:id,title,company,location,salary,type');

        return response()->json([
            'id' => $response->id,
            'vacancy_id' => $response->vacancy_id,
            'status' => $response->status,
            'applied_at' => $response->applied_at,
            'name' => $response->name,
            'surname' => $response->surname,
            'email' => $response->email,
            'phone' => $response->phone,
            'about' => $response->about,
            'social_url' => $response->social_url,
            'resume_url' => $response->resume_url,
            'vacancy' => [
                'id' => $response->vacancy->id,
                'title' => $response->vacancy->title,
                'company' => $response->vacancy->company,
                'location' => $response->vacancy->location,
                'salary' => $response->vacancy->salary,
                'type' => $response->vacancy->type,
            ]
        ]);
    }

    /**
     * Создание нового отклика на вакансию
     */
    public function store(StoreResponseRequest $request, Vacancy $vacancy): JsonResponse
    {
        // Проверка: вакансия должна быть опубликована
        if ($vacancy->status !== 'published') {
            return response()->json(['message' => 'Вакансия недоступна для откликов'], 403);
        }

        // Проверка: отклик на эту вакансию уже существует
        if (Response::where('user_id', Auth::id())
            ->where('vacancy_id', $vacancy->id)
            ->exists()) {
            return response()->json(['message' => 'Отклик на эту вакансию уже отправлен'], 409);
        }

        // Обработка файла резюме (если загружен)
        $resumeUrl = $request->resume_url;
        if ($request->hasFile('resume_file')) {
            $path = $request->file('resume_file')->store('resumes', 'public');
            $resumeUrl = Storage::url($path);
        }

        $response = Response::create([
            'vacancy_id' => $vacancy->id,
            'user_id' => Auth::id(),
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'phone' => $request->phone,
            'about' => $request->about,
            'social_url' => $request->social_url,
            'resume_url' => $resumeUrl,
            'status' => 'submitted',
            'applied_at' => now(),
        ]);

        return response()->json([
            'id' => $response->id,
            'user_id' => $response->user_id,
            'vacancy_id' => $response->vacancy_id,
            'name' => $response->name,
            'surname' => $response->surname,
            'email' => $response->email,
            'phone' => $response->phone,
            'about' => $response->about,
            'social_url' => $response->social_url,
            'resume_url' => $response->resume_url,
            'applied_at' => $response->applied_at,
            'status' => $response->status,
        ], 201);
    }

    /**
     * Редактирование отклика
     */
    public function update(UpdateResponseRequest $request, Response $response): JsonResponse
    {
        // Проверка прав доступа: только владелец отклика
        if ($response->user_id !== Auth::id()) {
            return response()->json(['message' => 'Доступ запрещён'], 403);
        }

        // Обработка файла резюме (если загружен)
        $resumeUrl = $request->resume_url ?? $response->resume_url;
        if ($request->hasFile('resume_file')) {
            $path = $request->file('resume_file')->store('resumes', 'public');
            $resumeUrl = Storage::url($path);
        }

        $response->update([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'phone' => $request->phone,
            'about' => $request->about,
            'social_url' => $request->social_url,
            'resume_url' => $resumeUrl,
        ]);

        return response()->json([
            'id' => $response->id,
            'user_id' => $response->user_id,
            'vacancy_id' => $response->vacancy_id,
            'name' => $response->name,
            'surname' => $response->surname,
            'email' => $response->email,
            'phone' => $response->phone,
            'about' => $response->about,
            'social_url' => $response->social_url,
            'resume_url' => $response->resume_url,
            'created_at' => $response->created_at,
            'updated_at' => $response->updated_at,
            'status' => $response->status,
        ]);
    }

    /**
     * Удаление отклика
     */
    public function destroy(Response $response): JsonResponse
    {
        // Проверка прав доступа: только владелец отклика или работодатель вакансии
        if ($response->user_id !== Auth::id() && $response->vacancy->employer_id !== Auth::id()) {
            return response()->json(['message' => 'Доступ запрещён'], 403);
        }

        $response->delete();

        return response()->json(['message' => 'Отклик успешно удален'], 200);
    }

    /**
     * Отправка отклика из избранного (альтернативный маршрут)
     */
    public function storeFromFavorite(StoreResponseRequest $request, Vacancy $vacancy): JsonResponse
    {
        return $this->store($request, $vacancy);
    }
}
