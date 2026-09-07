<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'service_category_id', 'name', 'slug', 'description', 'price', 'price_max',
        'duration', 'image', 'is_featured', 'is_active', 'position',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getPriceLabelAttribute(): string
    {
        return $this->price_max
            ? fcfa($this->price).' – '.fcfa($this->price_max)
            : fcfa($this->price);
    }

    public function getDurationLabelAttribute(): string
    {
        $h = intdiv($this->duration, 60);
        $m = $this->duration % 60;

        return trim(($h ? $h.'h' : '').($m ? ($h ? sprintf('%02d', $m) : $m.' min') : ''));
    }
}
