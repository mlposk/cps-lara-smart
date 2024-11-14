<?php

namespace App\Recommendation\Infrastructure\EloquentModels;

use Illuminate\Database\Eloquent\Model;

class TaskEloquentModel extends Model
{
    protected $table = 'recommendation_task';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'response',
        'query',
        'model',
        'source',
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
