<?php

namespace App\Models;

use App\Enums\CartState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_active',
        'user_id',
    ];

    public function scopeActive($query): mixed
    {
        return $query->where('is_active', CartState::Active);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}
