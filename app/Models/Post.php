<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title', 'slug', 'category', 'excerpt', 'body', 'image',
        'reading_time', 'is_published', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    /** Le corps de l'article : paragraphes et sous-titres (« ## Titre »). */
    public function getBlocksAttribute(): array
    {
        return collect(preg_split('/\n\s*\n/', (string) $this->body))
            ->map(fn ($block) => trim($block))
            ->filter()
            ->map(fn ($block) => str_starts_with($block, '## ')
                ? ['type' => 'heading', 'text' => mb_substr($block, 3)]
                : (str_starts_with($block, '- ')
                    ? ['type' => 'list', 'items' => array_map(
                        fn ($l) => ltrim($l, '- '),
                        preg_split('/\n/', $block)
                    )]
                    : ['type' => 'paragraph', 'text' => $block]))
            ->values()
            ->all();
    }
}
