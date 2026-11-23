<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'paid_at' => 'datetime',
            'picked_up_at' => 'datetime',
            'returned_at' => 'datetime',
            'is_insured' => 'boolean',
            'is_late' => 'boolean',
            'total_days' => 'integer',
            'unique_code' => 'integer',
            'late_days' => 'integer',
            'total_price' => 'decimal:2',
            'late_fee' => 'decimal:2',
            'damage_fee' => 'decimal:2',
        ];
    }

    public function getPayableTotalAttribute(): int
    {
        return (int) round((float) $this->total_price) + (int) ($this->unique_code ?? 0);
    }

    public function getDailyAverageRateAttribute(): float
    {
        $days = max(1, (int) $this->total_days);

        return (float) $this->total_price / $days;
    }

    public function getTotalPenaltyAttribute(): float
    {
        return (float) $this->late_fee + (float) $this->damage_fee;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function handoverStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handover_staff_id');
    }

    public function bookingItems(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function cancellations(): HasMany
    {
        return $this->hasMany(Cancellation::class);
    }

    public function cancellation(): HasOne
    {
        return $this->hasOne(Cancellation::class)->latestOfMany();
    }

    public function damageReports(): HasMany
    {
        return $this->hasMany(DamageReport::class);
    }
}
