<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Response extends Model
{
    use HasFactory;

    /**
     * Атрибуты, которые можно массово присваивать.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vacancy_id',
        'user_id',
        'name',
        'surname',
        'email',
        'phone',
        'about',
        'social_url',
        'resume_url',
        'status',
        'applied_at',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы в даты.
     *
     * @var array<string, string>
     */
    protected $dates = [
        'applied_at',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы в определённые типы.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'vacancy_id' => 'integer',
        'user_id' => 'integer',
    ];

    /**
     * Связь: отклик принадлежит пользователю (соискателю).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Связь: отклик принадлежит вакансии.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }

    /**
     * Получить полное имя соискателя.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->name} {$this->surname}");
    }
}
