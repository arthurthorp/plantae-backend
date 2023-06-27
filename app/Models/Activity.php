<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'description',
        'type',
        'status',
        'estimate_date',
        'execution_date',
        'charge_in',
        'plantation_id',
        'agricultural_input_id',
        'estimate_produtivity',
        'real_produtivity',
        'quantity_used',
        'price'
    ];

    public function plantation(): BelongsTo
    {
        return $this->belongsTo(Plantation::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(History::class);
    }
}
