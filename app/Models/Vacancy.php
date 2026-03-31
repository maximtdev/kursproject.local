<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vacancy extends Model
{
    use HasFactory;

    /**
     * Атрибуты, которые можно массово присваивать.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'company',
        'location',
        'salary',
        'type',
        'description',
        'requirements',
        'status',
        'employer_id',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы в определённые типы.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'requirements' => 'array', // Автоматическое преобразование JSON в массив и обратно
        'employer_id' => 'integer',
    ];

    /**
     * Связь: вакансия принадлежит работодателю (пользователю).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    /**
     * Связь: вакансия имеет много откликов.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    /**
     * Связь: вакансия имеет много записей в избранном.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Область запроса: только опубликованные вакансии.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
