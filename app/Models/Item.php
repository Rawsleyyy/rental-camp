<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_package' => 'boolean',
            'total_stock' => 'integer',
            'daily_rate' => 'integer',
        ];
    }

    protected function stock(): Attribute
    {
        return Attribute::get(fn (): int => (int) $this->total_stock);
    }

    protected function pricePerDay(): Attribute
    {
        return Attribute::get(fn (): int => (int) $this->daily_rate);
    }

    protected function image(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->image_path);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function bookingItems(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function damageReports(): HasMany
    {
        return $this->hasMany(DamageReport::class);
    }
}
