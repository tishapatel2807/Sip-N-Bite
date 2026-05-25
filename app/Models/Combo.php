<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    protected $fillable = [
        'title',
        'tagline',
        'badge',
        'item_ids',
        'regular_price',
        'combo_price',
        'is_active',
        'image',
    ];

    protected $casts = [
        'item_ids' => 'array',
        'regular_price' => 'decimal:2',
        'combo_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function getItemsAttribute()
    {
        $ids = $this->item_ids ?? [];

        if (empty($ids)) {
            return collect();
        }

        $foods = Food::with('category')
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        return collect($ids)
            ->map(fn ($id) => $foods->get($id))
            ->filter()
            ->values();
    }
}
