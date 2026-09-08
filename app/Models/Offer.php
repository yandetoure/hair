<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Offer extends Model
{
    protected $fillable = [
        'name', 'slug', 'tagline', 'description', 'price', 'compare_price',
        'duration', 'includes', 'image', 'is_featured', 'is_active', 'position',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /** Les prestations incluses, une par ligne. */
    public function getLinesAttribute(): Collection
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $this->includes))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values();
    }

    public function getDurationLabelAttribute(): ?string
    {
        if (! $this->duration) {
            return null;
        }

        $h = intdiv($this->duration, 60);
        $m = $this->duration % 60;

        return trim(($h ? $h.'h' : '').($m ? ($h ? sprintf('%02d', $m) : $m.' min') : ''));
    }

    public function getSavingAttribute(): ?int
    {
        return $this->compare_price && $this->compare_price > $this->price
            ? $this->compare_price - $this->price
            : null;
    }
}
