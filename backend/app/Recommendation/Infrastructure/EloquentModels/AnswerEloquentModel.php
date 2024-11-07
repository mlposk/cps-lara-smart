<?php

namespace App\Recommendation\Infrastructure\EloquentModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AnswerEloquentModel extends Model
{

    protected $table = 'answers';

    protected $fillable = ['recommendation_id', 'query', 'answer'];

    // Связь с пользователем
    public function recommendation(): HasOne
    {
        return $this->hasOne(RecommendationEloquentModel::class, 'recommendation_id');
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    function toArray(): array

    {
        return [];
    }
}
