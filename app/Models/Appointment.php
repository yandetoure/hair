<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Appointment extends Model
{
    public const STATUSES = [
        'pending' => 'En attente',
        'confirmed' => 'Confirmé',
        'completed' => 'Terminé',
        'cancelled' => 'Annulé',
        'no_show' => 'Absent',
    ];

    protected $fillable = [
        'reference', 'service_id', 'staff_member_id', 'customer_name', 'customer_phone',
        'customer_email', 'starts_at', 'ends_at', 'price', 'status', 'notes', 'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Appointment $appointment) {
            $appointment->reference ??= 'RDV-'.Str::upper(Str::random(6));
        });
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'staff_member_id');
    }

    public function scopeBetween(Builder $query, Carbon $from, Carbon $to): Builder
    {
        return $query->whereBetween('starts_at', [$from, $to]);
    }

    /** RDV qui comptent dans le chiffre d'affaires / l'occupation. */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ['pending', 'confirmed', 'completed']);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-800 ring-amber-200',
            'confirmed' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
            'completed' => 'bg-sky-100 text-sky-800 ring-sky-200',
            'cancelled' => 'bg-rose-100 text-rose-700 ring-rose-200',
            default => 'bg-slate-100 text-slate-600 ring-slate-200',
        };
    }
}
