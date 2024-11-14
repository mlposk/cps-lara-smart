<?php

namespace App\Recommendation\Infrastructure\EloquentModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string| $uuid
 * @property string $query
 * @property string|null $answer
 */
class RecommendationEloquentModel extends Model
{
    protected $table = 'recommendations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'query',
        'uuid',
        'answer',
        'uuid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    // Связь с сессией
    public function answers(): HasMany
    {
        return $this->hasMany(AnswerEloquentModel::class, 'recommendation_id');
    }
}
