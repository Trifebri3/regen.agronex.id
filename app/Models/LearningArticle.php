<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'slug',
    'title',
    'topic',
    'difficulty_level',
    'reading_time_minutes',
    'summary',
    'key_takeaways',
    'step_by_step',
    'practical_example',
    'is_published',
    'sort_order',
])]
class LearningArticle extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'reading_time_minutes' => 'integer',
            'key_takeaways' => 'array',
            'step_by_step' => 'array',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    #[Scope]
    protected function published(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort_order')->orderBy('id');
    }
}
