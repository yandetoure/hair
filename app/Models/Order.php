<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    public const STATUSES = [
        'pending' => 'À traiter',
        'confirmed' => 'Confirmée',
        'ready' => 'Prête',
        'delivered' => 'Livrée',
        'cancelled' => 'Annulée',
    ];

    protected $fillable = [
        'reference', 'customer_name', 'customer_phone', 'customer_email', 'delivery_mode',
        'address', 'subtotal', 'delivery_fee', 'total', 'status', 'notes',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->reference ??= 'CMD-'.Str::upper(Str::random(6));
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', '!=', 'cancelled');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-800 ring-amber-200',
            'confirmed' => 'bg-sky-100 text-sky-800 ring-sky-200',
            'ready' => 'bg-indigo-100 text-indigo-800 ring-indigo-200',
            'delivered' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
            default => 'bg-rose-100 text-rose-700 ring-rose-200',
        };
    }
}
