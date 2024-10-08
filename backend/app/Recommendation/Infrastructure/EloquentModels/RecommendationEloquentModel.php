<?php

namespace App\Recommendation\Infrastructure\EloquentModels;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int|null $id
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
        'answer'
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

}
